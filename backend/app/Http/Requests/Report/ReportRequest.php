<?php

namespace App\Http\Requests\Report;

use App\Enums\ReportType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReportRequest extends FormRequest
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
            'type' => ['required', 'string', Rule::enum(ReportType::class)],
            'counterparty_ids' => ['nullable', 'array'],
            'counterparty_ids.*' => ['nullable', 'integer', 'exists:counterparties,id'],
            'agreement_ids' => ['nullable', 'array'],
            'agreement_ids.*' => ['nullable', 'integer', 'exists:agreements,id'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
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
            'type' => 'тип отчета',
            'counterparty_id' => 'id контрагента',
            'agreement_id' => 'id договора',
            'from' => 'с даты',
            'to' => 'по дату',
        ];
    }
}
