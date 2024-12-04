<?php

namespace App\Http\Resources\Handbook;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CounterpartySlimResource extends JsonResource
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
            'is_external' => (bool)$this->external_id,
        ];
    }
}
