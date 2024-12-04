<?php

namespace App\Http\Requests\Handbook;

use Illuminate\Foundation\Http\FormRequest;
class CounterpartyRequest extends FormRequest
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
            'inn' => ['nullable', 'numeric', 'digits_between:10,12'],
            'okpo' => ['nullable', 'numeric', 'digits_between:8,14'],
            'ogrn' => ['nullable', 'numeric', 'digits_between:13,15'],

            'bik' => ['nullable', 'numeric', 'digits:9'],
            'rs' => ['nullable', 'numeric', 'digits:20'],
            'ks' => ['nullable', 'numeric', 'digits:20'],
            'bank' => ['nullable', 'string'],

            'contact_name' => ['nullable', 'string'],
            'contact_job' => ['nullable', 'string'],
            'contact_phone' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'string'],
            'contact_link' => ['nullable', 'string'],

            'name' => ['required', 'string'],
            'actual_address' => ['nullable', 'string'],
            'legal_address' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'email' => ['nullable', 'string'],
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
            'inn' => 'ИНН',
            'okpo' => 'ОКПО',
            'ogrn' => 'ОГРН',

            'bik' => 'БИК',
            'rs' => 'р/с',
            'ks' => 'к/с',
            'bank' => 'банк',

            'contact_name' => 'контактное лицо',
            'contact_job' => 'должность',
            'contact_phone' => 'телефон конт. лица',
            'contact_email' => 'почта конт. лица',
            'contact_link' => 'сайт',

            'name' => 'наименование компании',
            'actual_address' => 'физ. адрес',
            'legal_address' => 'юр. адрес',
            'phone' => 'телефон компании',
            'email' => 'почта компании',
        ];
    }
}
