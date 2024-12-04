<?php

namespace App\Http\Requests\Order;

use App\Enums\CurrencyCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MassStoreEstimateRequest extends FormRequest
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
            'estimates' => ['required', 'array'],
            'estimates.*.counterparty_to_id' => ['required', 'integer', 'exists:counterparties,id'],
            'estimates.*.counterparty_from_id' => ['required', 'integer', 'exists:counterparties,id'],
            'estimates.*.order_id' => ['required', 'integer', 'exists:orders,id'],
            'estimates.*.sort' => ['nullable', 'integer'],
            'estimates.*.currency_code' => ['required', 'string', Rule::in(CurrencyCode::values())],
            'estimates.*.payment_periods' => ['nullable', 'array'],
            'estimates.*.payment_periods.*.payment_date' => ['required', 'string'],
            'estimates.*.payment_periods.*.percent' => ['required', 'numeric'],
            'estimates.*.payment_periods.*.sum' => ['required', 'numeric'],
            'estimates.*.payment_periods.*.is_income' => ['required', 'boolean'],
            'estimates.*.payment_periods.*.currency_code' => ['required', 'string', Rule::in(CurrencyCode::values())],
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
            'estimates.*.counterparty_to_id' => 'к контрагенту',
            'estimates.*.counterparty_from_id' => 'от контрагента',
            'estimates.*.order_id' => 'заказ',
            'estimates.*.sort' => 'индекс сортировки',
            'estimates.*.currency_code' => 'код валюты',
            'estimates.*.payment_periods' => 'массив периодов оплат',
            'estimates.*.payment_periods.*.payment_date' => 'дата платежа',
            'estimates.*.payment_periods.*.percent' => 'процент',
            'estimates.*.payment_periods.*.sum' => 'сумма',
            'estimates.*.payment_periods.*.is_income' => 'доход/расход',
            'estimates.*.payment_periods.*.currency_code' => 'код валюты',
        ];
    }
}
