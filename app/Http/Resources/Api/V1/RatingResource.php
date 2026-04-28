<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $createdAt = $this->created_at;

        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'user' => new UserResource($this->whenLoaded('user')),
            'build_id' => $this->build_id,
            'created_at' => is_string($createdAt) ? $createdAt : $createdAt?->toString(),
        ];
    }
}
