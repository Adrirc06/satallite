<?php

namespace App\Http\Controllers\Api\V1\Components;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\RamResource;
use App\Models\Ram;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Ram::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%'.$request->query('name').'%');
        }

        $items = $query->paginate(20)->appends($request->query());

        return RamResource::collection($items);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): RamResource
    {
        $item = Ram::findOrFail($id);

        return new RamResource($item);
    }

    /**
     * Display RAMs by RAM type ID.
     */
    public function byRamType($ramTypeId): AnonymousResourceCollection
    {
        $items = Ram::where('ram_type_id', $ramTypeId)->paginate(20);

        return RamResource::collection($items);
    }
}
