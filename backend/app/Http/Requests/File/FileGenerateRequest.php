<?php

namespace App\Http\Requests\File;

use App\Enums\GenerateType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FileGenerateRequest extends FormRequest
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
        $extensions = implode(',', array_keys(GenerateType::from($this->input('model_type'))->data()));

        return [
            'model_type' => ['required', 'string', Rule::enum(GenerateType::class)],
            'extension' => ['required', 'string', 'in:' . $extensions],

            // GenerateType::Estimate
            'model_id' => ['required_if:model_type,' . GenerateType::Estimate->value, 'required_if:model_type,agreement', 'integer'],

            // GenerateType::ReportBalance
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
            'model_type' => 'тип сущности',
            'model_id' => 'идентификатор сущности',
            'extension' => 'формат файла',
        ];
    }
}
