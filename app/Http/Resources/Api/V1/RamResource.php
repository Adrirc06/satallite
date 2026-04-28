<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class RamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if (array_key_exists('ram_type_id', $data)) {
            $data['ram_type'] = $data['ram_type_id'] ? DB::table('ram_types')->find($data['ram_type_id']) : null;
            unset($data['ram_type_id']);
        }

        return $data;
    }
}
