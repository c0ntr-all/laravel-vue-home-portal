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
            'tag' => 'required|string|max:30',
            'parent_id' => 'sometimes|int',
            'common' => 'required|boolean',
        ];
    }
}
