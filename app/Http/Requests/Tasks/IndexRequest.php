<?php

namespace App\Http\Requests\Tasks;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        //Временно, для тестов
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string'
        ];
    }
}
