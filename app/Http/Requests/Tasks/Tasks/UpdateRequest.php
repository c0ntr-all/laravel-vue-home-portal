<?php

namespace App\Http\Requests\Tasks\Tasks;

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
            'title' => 'sometimes|string|max:30',
            'content' => 'sometimes|max:1000'
        ];
    }
}
