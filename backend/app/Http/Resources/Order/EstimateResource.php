<?php

namespace App\Http\Resources\Order;

use App\Enums\CurrencyCode;
use App\Http\Resources\Handbook\CounterpartySlimResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstimateResource extends JsonResource
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
            'counterparty_to' => new CounterpartySlimResource($this->whenLoaded('counterparty_to')),
            'counterparty_from' => new CounterpartySlimResource($this->whenLoaded('counterparty_from')),
            'sort' => $this->sort,
            'positions' => $this->whenNotNull((new EstimatePositionCollection($this->whenLoaded('positions')))->collection),
            'payment_periods' => $this->whenNotNull((new PaymentPeriodCollection($this->whenLoaded('payment_periods')))->collection),
            'currency_code' => !blank($this->currency_code) ? [
                'id' => $this->currency_code,
                'name' => CurrencyCode::from($this->currency_code)->value,
            ] : null,
        ];
    }
}
