<?php

namespace App\Http\Requests\Tasks\TaskLists;

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
            'tasks.title' => 'required|string|max:30',
        ];
    }
}