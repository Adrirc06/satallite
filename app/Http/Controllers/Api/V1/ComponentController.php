<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    protected array $models = [
        'motherboard' => \App\Models\Motherboard::class,
        'cpu'         => \App\Models\Cpu::class,
        'ram'         => \App\Models\Ram::class,
        'drive'       => \App\Models\Drive::class,
        'gpu'         => \App\Models\Gpu::class,
        'psu'         => \App\Models\Psu::class,
        'chassis'     => \App\Models\Chassis::class,
    ];

    protected array $resources = [
        'motherboard' => \App\Http\Resources\Api\V1\MotherboardResource::class,
        'cpu'         => \App\Http\Resources\Api\V1\CpuResource::class,
        'ram'         => \App\Http\Resources\Api\V1\RamResource::class,
        'drive'       => \App\Http\Resources\Api\V1\DriveResource::class,
        'gpu'         => \App\Http\Resources\Api\V1\GpuResource::class,
        'psu'         => \App\Http\Resources\Api\V1\PsuResource::class,
        'chassis'     => \App\Http\Resources\Api\V1\ChassisResource::class,
    ];

    public function index(Request $request, string $type)
    {
        if (!array_key_exists($type, $this->models)) {
            return response()->json(['message' => 'Tipo de componente no encontrado'], 404);
        }

        $model = $this->models[$type];
        $resource = $this->resources[$type];

        $query = $model::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filters mapping based on query string:
        $filters = [
            'socket_id',
            'form_factor_id',
            'ram_type_id',
            'drive_type_id',
            'psu_type_id',
            'chassis_type_id'
        ];

        foreach ($filters as $filter) {
            if ($request->filled($filter) && in_array($filter, (new $model)->getFillable())) {
                $query->where($filter, $request->$filter);
            }
        }

        return $resource::collection($query->paginate(15));
    }
}
