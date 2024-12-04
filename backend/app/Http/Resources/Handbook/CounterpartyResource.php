<?php

namespace App\Http\Resources\Handbook;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CounterpartyResource extends JsonResource
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

            'inn' => $this->inn,
            'okpo' => $this->okpo,
            'ogrn' => $this->ogrn,

            'bik' => $this->bik,
            'rs' => $this->rs,
            'ks' => $this->ks,
            'bank' => $this->bank,

            'contact_name' => $this->contact_name,
            'contact_job' => $this->contact_job,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,
            'contact_link' => $this->contact_link,

            'name' => $this->name,
            'actual_address' => $this->actual_address,
            'legal_address' => $this->legal_address,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }
}
