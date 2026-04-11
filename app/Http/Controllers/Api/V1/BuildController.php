<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreBuildRequest;
use App\Http\Resources\Api\V1\BuildResource;
use App\Models\Build;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BuildController extends Controller
{
    /**
     * Display a listing of the most recent builds.
     */
    public function index(): AnonymousResourceCollection
    {
        $builds = Build::with(['user', 'cpu', 'gpu', 'ram', 'motherboard', 'psu', 'drive', 'chassis'])
            ->latest()
            ->paginate(15);

        return BuildResource::collection($builds);
    }

    /**
     * Display all builds for a specific user.
     */
    public function userBuilds(User $user): AnonymousResourceCollection
    {
        $builds = $user->builds()
            ->with(['cpu', 'gpu', 'ram', 'motherboard', 'psu', 'drive', 'chassis'])
            ->latest()
            ->paginate(15);

        return BuildResource::collection($builds);
    }

    /**
     * Store a newly created build in storage.
     */
    public function store(StoreBuildRequest $request): BuildResource
    {
        // La validación ya ocurrió en el StoreBuildRequest
        $build = request()->user()->builds()->create($request->validated());

        // Cargamos las relaciones para la respuesta
        $build->load(['user', 'cpu', 'gpu', 'ram', 'motherboard', 'psu', 'drive', 'chassis']);

        return new BuildResource($build);
    }
}
