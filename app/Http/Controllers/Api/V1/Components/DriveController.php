<?php

namespace App\Http\Controllers\Api\V1\Components;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DriveResource;
use App\Models\Drive;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DriveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Drive::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%'.$request->query('name').'%');
        }

        $items = $query->paginate(20)->appends($request->query());

        return DriveResource::collection($items);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): DriveResource
    {
        $item = Drive::findOrFail($id);

        return new DriveResource($item);
    }
}
