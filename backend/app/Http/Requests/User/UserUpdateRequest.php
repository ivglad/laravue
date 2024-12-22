<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'username' => ['required', 'string', 'min:3'],
            'name' => ['required', 'string', 'min:2'],
            'surname' => ['nullable', 'string', 'min:2'],
            'patronymic' => ['nullable', 'string', 'min:2'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'min:6'],
            'job' => ['nullable', 'string', 'min:3'],
            'is_admin' => ['nullable', 'boolean'],
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
            'username' => 'логин',
            'name' => 'имя',
            'surname' => 'фамилия',
            'patronymic' => 'отчество',
            'email' => 'почта',
            'phone' => 'телефон',
            'job' => 'должность',
        ];
    }
}
