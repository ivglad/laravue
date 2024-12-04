<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
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
        $validationRules = [
            'counterparty_id' => ['required', 'integer', 'exists:counterparties,id'],
            'agreement_id' => ['nullable', 'integer', 'exists:agreements,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
        if (!Auth::user()->is_admin) {
            $validationRules['user_id'] = ['required', 'integer', 'exists:users,id', Rule::in(Auth::id())];
        }
        return $validationRules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'counterparty_id' => 'контрагент',
            'agreement_id' => 'договор',
            'user_id' => 'менеджер',
        ];
    }
}
