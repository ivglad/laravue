<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'payment_date' => $this->payment_date,
            'amount' => $this->amount,
            'is_income' => $this->is_income,
            'currency_value' => $this->currency_value,
        ];
    }
}
