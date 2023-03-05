<?php

namespace App\Http\Requests\Comment;

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
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string',
            'content' => 'required|string|max:2048'
        ];
    }
}
