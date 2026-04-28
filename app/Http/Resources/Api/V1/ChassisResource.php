<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ChassisResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if (array_key_exists('chassis_type_id', $data)) {
            $data['chassis_type'] = $data['chassis_type_id'] ? DB::table('chassis_types')->find($data['chassis_type_id']) : null;
            unset($data['chassis_type_id']);
        }

        return $data;
    }
}
