<?php

namespace App\Http\Resources\Handbook;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
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
            'is_external' => (bool)$this->external_id,
            'name' => $this->name,
            'type' => $this->type,
            'is_tax' => $this->is_tax,
            'is_transport' => $this->is_transport,
        ];
    }
}
