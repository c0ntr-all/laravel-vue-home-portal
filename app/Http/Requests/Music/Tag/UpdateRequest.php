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
            'name' => 'required|max:50',
            'content' => 'sometimes|max:30000'
        ];
    }
}
