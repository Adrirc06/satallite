<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\RatingResource;
use App\Models\Build;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

class RatingController extends Controller
{
    public function index(Build $build): AnonymousResourceCollection
    {
        return RatingResource::collection($build->ratings()->with('user')->latest()->paginate(15));
    }

    public function store(Request $request, Build $build): RatingResource
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        if ($build->ratings()->where('user_id', $request->user()->id)->exists()) {
            throw ValidationException::withMessages([
                'build' => ['Ya has valorado esta build.'],
            ]);
        }

        $rating = new Rating($validated);
        $rating->user_id = $request->user()->id;
        $build->ratings()->save($rating);

        return new RatingResource($rating->load('user'));
    }
}
