<?php

namespace App\Http\Resources\Order;

use App\Enums\CurrencyCode;
use App\Http\Resources\Agreement\AgreementResource;
use App\Http\Resources\File\FileCollection;
use App\Http\Resources\File\FileResource;
use App\Http\Resources\Handbook\PositionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstimatePositionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'currency_code' => !blank($this->currency_code) ? [
                'id' => $this->currency_code,
                'name' => CurrencyCode::from($this->currency_code)->value,
            ] : null,
            'currency_value' => $this->currency_value,
            'currency_increase' => $this->currency_increase,
            'price' => $this->price,
            'sum' => $this->sum,
            'sum_tax' => $this->sum_tax,
            'weight' => $this->weight,
            'position' => $this->whenNotNull(new PositionResource($this->whenLoaded('position'))),
            'payment_periods' => $this->whenNotNull((new PaymentPeriodCollection($this->whenLoaded('payment_periods')))->collection),
            'agreement' => $this->whenNotNull(new AgreementResource($this->whenLoaded('agreement'))),
            'is_income' => $this->is_income,
            'foreign_tax' => $this->foreign_tax,
            'meta' => $this->meta,
            'files' => $this->whenNotNull((new FileCollection($this->whenLoaded('media')))->collection),

        ];
        return $result;
    }
}
