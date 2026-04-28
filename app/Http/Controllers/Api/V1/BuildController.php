<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreBuildRequest;
use App\Http\Resources\Api\V1\BuildResource;
use App\Models\Build;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BuildController extends Controller
{
    /**
     * Display a listing of the most recent builds.
     */
    public function index(): AnonymousResourceCollection
    {
        $builds = Build::with(['user', 'cpu', 'gpu', 'ram', 'motherboard', 'psu', 'drive', 'chassis'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->latest()
            ->paginate(15);

        return BuildResource::collection($builds);
    }

    /**
     * Devuelve builds pÃºblicas aleatorias.
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
            abort(403, 'No tienes permiso para eliminar esta configuraciÃ³n.');
        }

        $build->delete();

        return response()->json(['message' => 'ConfiguraciÃ³n eliminada correctamente']);
    }
}
