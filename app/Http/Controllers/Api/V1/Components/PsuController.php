<?php

namespace App\Http\Controllers\Api\V1\Components;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PsuResource;
use App\Models\Psu;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PsuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Psu::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%'.$request->query('name').'%');
        }

        $items = $query->paginate(20)->appends($request->query());

        return PsuResource::collection($items);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): PsuResource
    {
        $item = Psu::findOrFail($id);

        return new PsuResource($item);
    }
}
