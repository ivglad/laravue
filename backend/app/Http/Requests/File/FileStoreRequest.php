<?php

namespace App\Http\Requests\File;

use App\Enums\FileModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FileStoreRequest extends FormRequest
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
            'model_type' => ['required', 'string', Rule::enum(FileModel::class)],
            'model_id' => ['required', 'integer'],
            'files' => ['required', 'array'],
            'files.*' => ['required', 'file'],
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
            'files' => 'файлы',
            'files.*' => 'файл',
        ];
    }
}
