<?php

namespace App\Http\Requests\Admin\Music\Tags;

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
            'name' => 'required|string|unique:music_tags|max:50',
            'content' => 'sometimes|string|max:30000',
            'parent_id' => 'sometimes|int',
            'is_base' => 'sometimes|boolean',
        ];
    }
}
