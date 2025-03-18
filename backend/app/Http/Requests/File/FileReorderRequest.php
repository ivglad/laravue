<?php

declare(strict_types=1);

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class FileReorderRequest
 *
 * @package App\Http\Requests\File
 */
class FileReorderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '.*' => ['required'],
        ];
    }
}
