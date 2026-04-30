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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $exactFilters = [
            'socket_id',
            'form_factor_id',
            'ram_type_id',
            'drive_type_id',
            'psu_type_id',
            'chassis_type_id',
            'modularity_id',
            'efficiency_id',
        ];

        foreach ($exactFilters as $filter) {
            if ($request->filled($filter) && in_array($filter, (new $model)->getFillable())) {
                $query->where($filter, $request->$filter);
            }
        }

        $this->applyRangeFilters($request, $type, $query);

        return $resource::collection($query->paginate(15));
    }

    public function filters(string $type): JsonResponse
    {
        if (! array_key_exists($type, $this->models)) {
            return response()->json(['message' => 'Tipo de componente no encontrado'], 404);
        }

        $data = match ($type) {
            'motherboard' => [
                'sockets' => DB::table('sockets')->orderBy('name')->get(['id', 'name']),
                'form_factors' => DB::table('form_factors')->orderBy('name')->get(['id', 'name']),
                'ram_types' => DB::table('ram_types')->orderBy('name')->get(['id', 'name']),
                'max_memory' => [
                    'min' => Motherboard::min('max_memory') ?? 0,
                    'max' => Motherboard::max('max_memory') ?? 0,
                ],
            ],
            'cpu' => [
                'sockets' => DB::table('sockets')->orderBy('name')->get(['id', 'name']),
                'cores' => [
                    'min' => Cpu::min('cores') ?? 0,
                    'max' => Cpu::max('cores') ?? 0,
                ],
            ],
            'ram' => [
                'ram_types' => DB::table('ram_types')->orderBy('name')->get(['id', 'name']),
                'capacity' => [
                    'min' => Ram::selectRaw('MIN(size * modules) as cap')->value('cap') ?? 0,
                    'max' => Ram::selectRaw('MAX(size * modules) as cap')->value('cap') ?? 0,
                ],
                'speed' => [
                    'min' => Ram::min('speed') ?? 0,
                    'max' => Ram::max('speed') ?? 0,
                ],
            ],
            'drive' => [
                'capacity' => [
                    'min' => Drive::min('size') ?? 0,
                    'max' => Drive::max('size') ?? 0,
                ],
            ],
            'gpu' => [
                'vram' => [
                    'min' => Gpu::min('vram') ?? 0,
                    'max' => Gpu::max('vram') ?? 0,
                ],
            ],
            'psu' => [
                'psu_types' => DB::table('psu_types')->orderBy('name')->get(['id', 'name']),
                'modularities' => DB::table('modularities')->orderBy('name')->get(['id', 'name']),
                'efficiencies' => DB::table('efficiencies')->orderBy('name')->get(['id', 'name']),
                'power' => [
                    'min' => Psu::min('power') ?? 0,
                    'max' => Psu::max('power') ?? 0,
                ],
            ],
            'chassis' => [
                'chassis_types' => DB::table('chassis_types')->orderBy('name')->get(['id', 'name']),
            ],
            default => [],
        };

        return response()->json($data);
    }

    private function applyRangeFilters(Request $request, string $type, Builder $query): void
    {
        if ($type === 'motherboard') {
            if ($request->filled('min_max_memory')) {
                $query->where('max_memory', '>=', (int) $request->min_max_memory);
            }
            if ($request->filled('max_max_memory')) {
                $query->where('max_memory', '<=', (int) $request->max_max_memory);
            }
        }

        if ($type === 'cpu') {
            if ($request->filled('min_cores')) {
                $query->where('cores', '>=', (int) $request->min_cores);
            }
            if ($request->filled('max_cores')) {
                $query->where('cores', '<=', (int) $request->max_cores);
            }
        }

        if ($type === 'ram') {
            if ($request->filled('min_capacity')) {
                $query->whereRaw('(size * modules) >= ?', [(int) $request->min_capacity]);
            }
            if ($request->filled('max_capacity')) {
                $query->whereRaw('(size * modules) <= ?', [(int) $request->max_capacity]);
            }
            if ($request->filled('min_speed')) {
                $query->where('speed', '>=', (int) $request->min_speed);
            }
            if ($request->filled('max_speed')) {
                $query->where('speed', '<=', (int) $request->max_speed);
            }
        }

        if ($type === 'drive') {
            if ($request->filled('min_capacity')) {
                $query->where('size', '>=', (int) $request->min_capacity);
            }
            if ($request->filled('max_capacity')) {
                $query->where('size', '<=', (int) $request->max_capacity);
            }
            if ($request->filled('drive_category')) {
                if ($request->drive_category === 'ssd') {
                    $query->where('drive_type_id', 1);
                } elseif ($request->drive_category === 'hdd') {
                    $query->where('drive_type_id', '!=', 1);
                }
            }
        }

        if ($type === 'gpu') {
            if ($request->filled('min_vram')) {
                $query->where('vram', '>=', (int) $request->min_vram);
            }
            if ($request->filled('max_vram')) {
                $query->where('vram', '<=', (int) $request->max_vram);
            }
        }

        if ($type === 'psu') {
            if ($request->filled('min_power')) {
                $query->where('power', '>=', (int) $request->min_power);
            }
            if ($request->filled('max_power')) {
                $query->where('power', '<=', (int) $request->max_power);
            }
        }
    }
}
