<?php

namespace App\Http\Requests\Handbook;

use App\Enums\PositionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PositionRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'type' => ['required', 'string', Rule::in(PositionType::values())],
            'is_tax' => ['required', 'boolean'],
            'is_transport' => ['required', 'boolean'],
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
            'name' => 'наименование',
            'type' => 'тип',
            'is_tax' => 'является пошлиной',
            'is_transport' => 'является транспортом',
        ];
    }
}
