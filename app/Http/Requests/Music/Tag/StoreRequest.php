<?php

namespace App\Http\Requests\Music\Tag;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:music_tags|max:30',
            'content' => 'sometimes|string|max:30000',
            'parent_id' => 'sometimes|int',
            'common' => 'sometimes|boolean',
        ];
    }
}
