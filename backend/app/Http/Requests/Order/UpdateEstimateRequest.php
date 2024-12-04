<?php

namespace App\Http\Requests\Order;

use App\Enums\CurrencyCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEstimateRequest extends FormRequest
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
            'counterparty_to_id' => ['required', 'integer', 'exists:counterparties,id'],
            'counterparty_from_id' => ['required', 'integer', 'exists:counterparties,id'],
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'sort' => ['nullable', 'integer'],
            'currency_code' => ['required', 'string', Rule::in(CurrencyCode::values())],
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
            'counterparty_to_id' => 'к контрагенту',
            'counterparty_from_id' => 'от контрагента',
            'order_id' => 'заказ',
            'sort' => 'индекс сортировки',
            'currency_code' => 'код валюты',
            'payment_periods' => 'массив периодов оплат',
            'payment_periods.*.payment_date' => 'дата платежа',
            'payment_periods.*.percent' => 'процент',
            'payment_periods.*.sum' => 'сумма',
            'payment_periods.*.is_income' => 'доход/расход',
            'payment_periods.*.currency_code' => 'код валюты',
        ];
    }
}
