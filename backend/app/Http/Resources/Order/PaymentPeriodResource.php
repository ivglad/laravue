<?php

namespace App\Http\Resources\Order;

use App\Enums\CurrencyCode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentPeriodResource extends JsonResource
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
            'percent' => $this->percent,
            'sum' => $this->sum,
            'paid' => round($this->payments()->sum('amount'), 2),
            'is_income' => $this->is_income,
            'currency_code' => !blank($this->currency_code) ? [
                'id' => $this->currency_code,
                'name' => CurrencyCode::from($this->currency_code)->value,
            ] : null,
        ];
    }
}
