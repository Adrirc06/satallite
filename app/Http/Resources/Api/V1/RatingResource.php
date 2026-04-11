<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'score' => $this->score,
            'comment' => $this->comment,
            'user' => new UserResource($this->whenLoaded('user')),
            'build_id' => $this->build_id,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
