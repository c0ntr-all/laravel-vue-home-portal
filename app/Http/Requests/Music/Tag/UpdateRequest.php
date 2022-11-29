<?php

namespace App\Http\Requests\Music\Tag;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|int',
            'name' => 'required|max:20',
            'common' => 'required|boolean',
        ];
    }
}
