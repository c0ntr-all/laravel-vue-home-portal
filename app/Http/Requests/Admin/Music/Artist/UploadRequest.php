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
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
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
            'preview' => 'sometimes|boolean',
        ];
    }
}
