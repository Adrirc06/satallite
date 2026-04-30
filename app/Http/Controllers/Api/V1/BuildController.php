<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreBuildRequest;
use App\Http\Resources\Api\V1\BuildResource;
use App\Models\Build;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BuildController extends Controller
{
    /**
     * Devuelve builds públicas aleatorias.
     */
    public function random(): AnonymousResourceCollection
    {
        $builds = Build::with(['user', 'cpu', 'gpu', 'ram', 'motherboard', 'psu', 'drive', 'chassis'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->where('is_public', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return BuildResource::collection($builds);
    }

    /**
     * Display all builds for a specific user.
     */
    public function userBuilds(User $user): AnonymousResourceCollection
    {
        $perPage = min((int) request('per_page', 15), 50);

        $query = $user->builds()
            ->with(['cpu', 'gpu', 'ram', 'motherboard', 'psu', 'drive', 'chassis'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->latest();

        if (request()->boolean('is_public')) {
            $query->where('is_public', true);
        }

        return BuildResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created build in storage.
     */
    public function store(StoreBuildRequest $request): BuildResource
    {
        $build = request()->user()->builds()->create($request->validated());

        // Cargamos las relaciones para la respuesta
        $build->load(['user', 'cpu', 'gpu', 'ram', 'motherboard', 'psu', 'drive', 'chassis'])
            ->loadAvg('ratings', 'rating')
            ->loadCount('ratings');

        return new BuildResource($build);
    }

    /**
     * Update the specified build in storage.
     */
    public function update(StoreBuildRequest $request, Build $build): BuildResource
    {
        if (request()->user()->id !== $build->user_id) {
            abort(403, 'No tienes permiso para modificar esta configuración.');
        }

        $build->update($request->validated());

        // Cargamos las relaciones para la respuesta
        $build->load(['user', 'cpu', 'gpu', 'ram', 'motherboard', 'psu', 'drive', 'chassis'])
            ->loadAvg('ratings', 'rating')
            ->loadCount('ratings');

        return new BuildResource($build);
    }

    /**
     * Remove the specified build from storage.
     */
    public function destroy(Build $build)
    {
        if (request()->user()->id !== $build->user_id) {
            abort(403, 'No tienes permiso para eliminar esta configuración.');
        }

        $build->delete();

        return response()->json(['message' => 'Configuración eliminada correctamente']);
    }

    /**
     * Generates an AI summary of the build's capabilities using Gemini.
     */
    public function aiSummary(Build $build): JsonResponse
    {
        $build->load(['cpu', 'motherboard', 'ram', 'gpu', 'drive', 'psu', 'chassis']);

        $lines = ["Configuración: {$build->name}", ''];

        if ($build->cpu) {
            $line = "- Procesador: {$build->cpu->name}";
            if ($build->cpu->cores) {
                $line .= ", {$build->cpu->cores} núcleos";
            }
            if ($build->cpu->frequency) {
                $line .= ", {$build->cpu->frequency}GHz base";
            }
            if ($build->cpu->tdp) {
                $line .= ", TDP {$build->cpu->tdp}W";
            }
            $lines[] = $line;
        }

        if ($build->motherboard) {
            $lines[] = "- Placa base: {$build->motherboard->name}";
        }

        if ($build->ram) {
            $totalRam = $build->ram->size * $build->ram->modules;
            $line = "- RAM: {$build->ram->name}, {$totalRam}GB";
            if ($build->ram->speed) {
                $line .= ", {$build->ram->speed}MHz";
            }
            $lines[] = $line;
        }

        if ($build->gpu) {
            $line = "- Tarjeta gráfica: {$build->gpu->name}, {$build->gpu->vram}GB VRAM";
            if ($build->gpu->tdp) {
                $line .= ", TDP {$build->gpu->tdp}W";
            }
            $lines[] = $line;
        }

        if ($build->drive) {
            $size = $build->drive->size >= 1000
                ? ($build->drive->size / 1000).'TB'
                : $build->drive->size.'GB';
            $lines[] = "- Almacenamiento: {$build->drive->name}, {$size}";
        }

        if ($build->psu) {
            $line = "- Fuente de alimentación: {$build->psu->name}";
            if ($build->psu->power) {
                $line .= ", {$build->psu->power}W";
            }
            $lines[] = $line;
        }

        if ($build->chassis) {
            $lines[] = "- Caja: {$build->chassis->name}";
        }

        $componentList = implode("\n", $lines);
        $prompt = "Eres un experto en hardware de PC. Analiza la siguiente configuración y escribe una reseña en español sobre sus capacidades: ¿Para qué usos es ideal (gaming, trabajo, edición de vídeo, streaming, uso cotidiano)? ¿Qué rendimiento se puede esperar? ¿Cuáles son sus puntos fuertes y sus limitaciones? Escribe en párrafos, sin listas, de forma directa y concisa (máximo 350 palabras).\n\n{$componentList}";

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key='.config('services.gemini.key');
        $payload = json_encode(['contents' => [['parts' => [['text' => $prompt]]]]]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_TIMEOUT => 30,
        ]);

        $result = curl_exec($ch);
        $curlError = curl_error($ch);

        if ($curlError) {
            return response()->json(['error' => 'Sin conexión con la IA'], 503);
        }

        $data = json_decode($result, true);

        if (isset($data['error'])) {
            $status = $data['error']['status'] ?? '';
            $message = match ($status) {
                'RESOURCE_EXHAUSTED' => 'La cuota de la IA está agotada. Inténtalo más tarde.',
                'INVALID_ARGUMENT' => 'Solicitud inválida a la IA.',
                'UNAUTHENTICATED' => 'Clave de la IA no válida.',
                default => 'Error de la IA: '.$status,
            };

            return response()->json(['error' => $message], 503);
        }

        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (! $text) {
            return response()->json(['error' => 'La IA no devolvió una respuesta válida.'], 503);
        }

        return response()->json(['summary' => $text]);
    }
}
