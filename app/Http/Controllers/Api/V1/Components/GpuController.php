<?php

namespace App\Http\Controllers\Api\V1\Components;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\GpuResource;
use App\Models\Gpu;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GpuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Gpu::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%'.$request->query('name').'%');
        }

        $items = $query->paginate(20)->appends($request->query());

        return GpuResource::collection($items);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): GpuResource
    {
        $item = Gpu::findOrFail($id);

        return new GpuResource($item);
    }
}
