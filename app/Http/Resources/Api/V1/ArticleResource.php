<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'banner_url' => $this->banner_url,
            'content' => $this->content,
            'date' => $this->date ? Carbon::parse($this->date)->format('d/m/Y') : null,
            'user' => new UserResource($this->whenLoaded('authorRelation')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
