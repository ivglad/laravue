<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
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
            'username' => ['required', 'string', 'min:3', 'unique:users'],
            'name' => ['required', 'string', 'min:2'],
            'surname' => ['nullable', 'string', 'min:2'],
            'patronymic' => ['nullable', 'string', 'min:2'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'min:6'],
            'job' => ['nullable', 'string', 'min:3'],
            'hex_color' => ['nullable', 'hex_color'],
            'is_admin' => ['nullable', 'boolean'],
            'password' => ['required', 'string', Password::min(8)->letters()->mixedCase()->numbers()],
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
            'password' => 'пароль',
        ];
    }
}
