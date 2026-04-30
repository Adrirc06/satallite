<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\RatingResource;
use App\Models\Build;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RatingController extends Controller
{
    public function store(Request $request, Build $build): RatingResource
    {
        $request->validate([
            'rating' => 'required|integer|min:0|max:100',
        ]);

        if ($build->ratings()->where('user_id', $request->user()->id)->exists()) {
            throw ValidationException::withMessages([
                'build' => ['Ya has valorado esta configuración.'],
            ]);
        }

        $rating = new Rating;
        $rating->rating = $request->input('rating');
        $rating->user_id = $request->user()->id;
        $build->ratings()->save($rating);

        return new RatingResource($rating->refresh()->load('user'));
    }
}
