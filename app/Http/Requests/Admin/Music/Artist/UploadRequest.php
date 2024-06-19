<?php

namespace App\Http\Requests\Admin\Music\Artist;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
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
            'path' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!file_exists($value)) {
                        $fail('The chosen catalog doesn\'t exists!');
                    }
                }
            ],
            'is_preview' => 'sometimes|boolean',
        ];
    }
}
