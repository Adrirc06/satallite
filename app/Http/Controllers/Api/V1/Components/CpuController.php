<?php

namespace App\Http\Controllers\Api\V1\Components;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CpuResource;
use App\Models\Cpu;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CpuController extends Controller
{
    /**
     * Display a listing of the CPUs.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Cpu::query();

        // B�squeda por nombre v�a query params (?name=Ryzen)
        if ($request->has('name')) {
            $query->where('name', 'like', '%'.$request->query('name').'%');
        }

        $cpus = $query->paginate(20)->appends($request->query());

        return CpuResource::collection($cpus);
    }

    /**
     * Display the specified CPU.
     */
    public function show(Cpu $cpu): CpuResource
    {

        return new CpuResource($cpu);
    }

    /**
     * Display CPUs by socket ID.
     */
    public function bySocket($socketId): AnonymousResourceCollection
    {
        $cpus = Cpu::where('socket_id', $socketId)->paginate(20);

        return CpuResource::collection($cpus);
    }
}
