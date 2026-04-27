<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ChassisResource;
use App\Http\Resources\Api\V1\CpuResource;
use App\Http\Resources\Api\V1\DriveResource;
use App\Http\Resources\Api\V1\GpuResource;
use App\Http\Resources\Api\V1\MotherboardResource;
use App\Http\Resources\Api\V1\PsuResource;
use App\Http\Resources\Api\V1\RamResource;
use App\Models\Chassis;
use App\Models\Cpu;
use App\Models\Drive;
use App\Models\Gpu;
use App\Models\Motherboard;
use App\Models\Psu;
use App\Models\Ram;
use Illuminate\Http\Request;

class ComponentController extends Controller
{
    protected array $models = [
        'motherboard' => Motherboard::class,
        'cpu' => Cpu::class,
        'ram' => Ram::class,
        'drive' => Drive::class,
        'gpu' => Gpu::class,
        'psu' => Psu::class,
        'chassis' => Chassis::class,
    ];

    protected array $resources = [
        'motherboard' => MotherboardResource::class,
        'cpu' => CpuResource::class,
        'ram' => RamResource::class,
        'drive' => DriveResource::class,
        'gpu' => GpuResource::class,
        'psu' => PsuResource::class,
        'chassis' => ChassisResource::class,
    ];

    public function index(Request $request, string $type)
    {
        if (! array_key_exists($type, $this->models)) {
            return response()->json(['message' => 'Tipo de componente no encontrado'], 404);
        }

        $model = $this->models[$type];
        $resource = $this->resources[$type];

        $query = $model::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        // Filters mapping based on query string:
        $filters = [
            'socket_id',
            'form_factor_id',
            'ram_type_id',
            'drive_type_id',
            'psu_type_id',
            'chassis_type_id',
        ];

        foreach ($filters as $filter) {
            if ($request->filled($filter) && in_array($filter, (new $model)->getFillable())) {
                $query->where($filter, $request->$filter);
            }
        }

        return $resource::collection($query->paginate(15));
    }
}
