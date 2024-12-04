<?php

namespace App\Http\Requests\Order;

use App\Enums\CurrencyCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EstimatePositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'estimate_id' => ['required', 'integer', 'exists:estimates,id'],
            'position_id' => ['required', 'integer', 'exists:positions,id'],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'currency_code' => ['nullable', 'string', Rule::in(CurrencyCode::values())],
            'currency_value' => ['nullable', 'numeric', 'min:0'],
            'currency_increase' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'price' => ['required', 'decimal:0,3', 'min:0'],
            'sum' => ['required', 'decimal:0,3', 'min:0'],
            'sum_tax' => ['required', 'decimal:0,3', 'min:0'],
            'agreement_id' => ['nullable', 'integer', 'exists:agreements,id'],
            'is_income' => ['required', 'boolean'],
            'meta' => ['nullable', 'array'],
            'foreign_tax' => ['nullable', 'numeric', 'min:0'],
            'payment_periods' => ['nullable', 'array'],
            'payment_periods.*.payment_date' => ['required', 'string'],
            'payment_periods.*.percent' => ['required', 'numeric'],
            'payment_periods.*.sum' => ['required', 'numeric'],
            'payment_periods.*.is_income' => ['required', 'boolean'],
            'payment_periods.*.currency_code' => ['required', 'string', Rule::in(CurrencyCode::values())],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'estimate_id' => 'смета',
            'position_id' => 'ID позиции',
            'position_type' => 'тип позиции',
            'quantity' => 'кол-во',
            'currency_code' => 'валюта',
            'currency_value' => 'курс',
            'currency_increase' => 'увеличение курса',
            'price' => 'цена за шт.',
            'sum' => 'сумма без НДС',
            'sum_tax' => 'сумм с НДС',
            'contract' => 'договор - основание',
            'is_income' => 'доход/расход',
            'payment_periods' => 'массив периодов оплат',
            'payment_periods.*.payment_date' => 'дата платежа',
            'payment_periods.*.percent' => 'процент',
            'payment_periods.*.sum' => 'сумма',
            'payment_periods.*.is_income' => 'доход/расход',
            'payment_periods.*.currency_code' => 'код валюты',
        ];
    }
}
