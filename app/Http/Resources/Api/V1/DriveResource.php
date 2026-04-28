<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class DriveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if (array_key_exists('drive_type_id', $data)) {
            $data['drive_type'] = $data['drive_type_id'] ? DB::table('drive_types')->find($data['drive_type_id']) : null;
            unset($data['drive_type_id']);
        }

        return $data;
    }
}
