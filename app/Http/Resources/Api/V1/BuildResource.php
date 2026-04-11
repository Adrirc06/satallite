<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuildResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user' => new UserResource($this->whenLoaded('user')),

            'components' => [
                'cpu' => new CpuResource($this->whenLoaded('cpu')),
                'gpu' => new GpuResource($this->whenLoaded('gpu')),
                'ram' => new RamResource($this->whenLoaded('ram')),
                'motherboard' => new MotherboardResource($this->whenLoaded('motherboard')),
                'psu' => new PsuResource($this->whenLoaded('psu')),
                'drive' => new DriveResource($this->whenLoaded('drive')),
                'chassis' => new ChassisResource($this->whenLoaded('chassis')),
            ],

            'ratings_avg' => $this->whenCounted('ratings', fn () => $this->ratings_avg_score),
            'created_at' => $this->created_at,
        ];
    }
}
