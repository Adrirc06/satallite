<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreBuildRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cpu_id' => 'required|exists:cpus,id',
            'gpu_id' => 'required|exists:gpus,id',
            'ram_id' => 'required|exists:rams,id',
            'motherboard_id' => 'required|exists:motherboards,id',
            'psu_id' => 'required|exists:psus,id',
            'drive_id' => 'required|exists:drives,id',
            'chassis_id' => 'required|exists:chassis,id',
        ];
    }
}
