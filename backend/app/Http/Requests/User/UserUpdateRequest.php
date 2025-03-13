<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        return $user->id === $this->route('user')->id && $user->can('user.update') ||
            $user->can('user.update.other');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'surname' => ['required', 'string', 'min:2'],
            'name' => ['required', 'string', 'min:2'],
            'patronymic' => ['nullable', 'string', 'min:2'],
            'email' => ['required', 'string', 'min:3', 'email', 'unique:users,email,' . $this->route('user')->id],
            'username' => ['required', 'string', 'min:3', 'unique:users,username,' . $this->route('user')->id],
            'hex_color' => ['nullable', 'hex_color'],
            'phone' => ['required', 'string'],
            'job_title' => ['nullable', 'string', 'min:3'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['nullable', 'integer', 'exists:roles,id']
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
            'name' => 'имя',
        ];
    }
}
