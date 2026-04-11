<?php

namespace App\Http\Controllers\Api\V1\Components;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MotherboardResource;
use App\Models\Motherboard;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MotherboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Motherboard::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%'.$request->query('name').'%');
        }

        $items = $query->paginate(20)->appends($request->query());

        return MotherboardResource::collection($items);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): MotherboardResource
    {
        $item = Motherboard::findOrFail($id);

        return new MotherboardResource($item);
    }

    /**
     * Display motherboards by socket ID.
     */
    public function bySocket($socketId): AnonymousResourceCollection
    {
        $items = Motherboard::where('socket_id', $socketId)->paginate(20);

        return MotherboardResource::collection($items);
    }

    /**
     * Display motherboards by RAM type ID.
     */
    public function byRamType($ramTypeId): AnonymousResourceCollection
    {
        $items = Motherboard::where('ram_type_id', $ramTypeId)->paginate(20);

        return MotherboardResource::collection($items);
    }
}
