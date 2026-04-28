<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class PsuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if (array_key_exists('psu_type_id', $data)) {
            $data['psu_type'] = $data['psu_type_id'] ? DB::table('psu_types')->find($data['psu_type_id']) : null;
            unset($data['psu_type_id']);
        }

        if (array_key_exists('modularity_id', $data)) {
            $data['modularity'] = $data['modularity_id'] ? DB::table('modularities')->find($data['modularity_id']) : null;
            unset($data['modularity_id']);
        }

        if (array_key_exists('efficiency_id', $data)) {
            $data['efficiency'] = $data['efficiency_id'] ? DB::table('efficiencies')->find($data['efficiency_id']) : null;
            unset($data['efficiency_id']);
        }

        return $data;
    }
}
