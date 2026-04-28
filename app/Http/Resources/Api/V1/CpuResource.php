<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class CpuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if (array_key_exists('socket_id', $data)) {
            $data['socket'] = $data['socket_id'] ? DB::table('sockets')->find($data['socket_id']) : null;
            unset($data['socket_id']);
        }

        if (array_key_exists('igpu_id', $data)) {
            $data['igpu'] = $data['igpu_id'] ? DB::table('igpus')->find($data['igpu_id']) : null;
            unset($data['igpu_id']);
        }

        return $data;
    }
}
