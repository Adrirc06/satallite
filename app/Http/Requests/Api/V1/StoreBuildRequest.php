<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Cpu;
use App\Models\Gpu;
use App\Models\Motherboard;
use App\Models\Psu;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreBuildRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:32'],
            'is_public' => ['nullable', 'boolean'],
            'motherboard_id' => ['required', 'integer', 'exists:motherboards,id'],
            'cpu_id' => ['nullable', 'integer', 'exists:cpus,id'],
            'gpu_id' => ['nullable', 'integer', 'exists:gpus,id'],
            'ram_id' => ['required', 'integer', 'exists:rams,id'],
            'psu_id' => ['required', 'integer', 'exists:psus,id'],
            'drive_id' => ['required', 'integer', 'exists:drives,id'],
            'chassis_id' => ['required', 'integer', 'exists:chassis,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->failed()) {
                return;
            }

            $data = $this->validated();

            $motherboardId = $data['motherboard_id'] ?? null;
            $cpuId = $data['cpu_id'] ?? null;
            $gpuId = $data['gpu_id'] ?? null;
            $psuId = $data['psu_id'] ?? null;

            $motherboard = Motherboard::find($motherboardId);
            $cpu = Cpu::find($cpuId);
            $gpu = Gpu::find($gpuId);
            $psu = Psu::find($psuId);

            // 1. CPU Validation: Required unless Motherboard has integrated CPU
            $isCpuIntegrated = false;
            if ($motherboard && $motherboard->socket_id) {
                $socket = DB::table('sockets')->where('id', $motherboard->socket_id)->first();
                if ($socket && str_contains(strtolower($socket->name), 'integrated')) {
                    $isCpuIntegrated = true;
                }
            }

            if (! $cpu && ! $isCpuIntegrated) {
                $validator->errors()->add('cpu_id', 'Aún no has seleccionado ningún componente para: Procesador');
            }

            // 2. GPU Validation: Required unless CPU has integrated GPU
            $hasIgpu = $cpu && $cpu->igpu_id !== null;

            if (! $gpu && ! $hasIgpu) {
                $validator->errors()->add('gpu_id', 'Aún no has seleccionado ningún componente para: Tarjeta gráfica');
            }

            // 3. PSU Power validation
            if ($psu) {
                $cpuTdp = $cpu ? ($cpu->tdp ?? 0) : 0;
                $gpuTdp = $gpu ? ($gpu->tdp ?? 0) : 0;
                $combinedTdp = $cpuTdp + $gpuTdp;

                if ($combinedTdp > ($psu->power ?? 0)) {
                    $validator->errors()->add('psu_id', "La suma del TDP del procesador y la gráfica ({$combinedTdp}W) supera la capacidad de la fuente de alimentación ({$psu->power}W).");
                }
            }
        });
    }
}
