<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'payment_date' => ['required', 'date'],
            'payment_period_id' => ['required', 'integer', 'exists:payment_periods,id'],
            'amount' => ['required', 'decimal:0,3'],
            'currency_value' => ['required', 'decimal:0,3'],
            'is_income' => ['required', 'boolean'],
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
            'payment_date' => 'дата платежа',
            'payment_period_id' => 'ID периода оплаты',
            'amount' => 'сумма',
            'currency_value' => 'валюта',
            'is_income' => 'доход/расход',
        ];
    }
}
