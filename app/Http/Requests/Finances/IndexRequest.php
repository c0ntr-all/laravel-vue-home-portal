<?php

namespace App\Http\Requests\Finances;

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
            'summ' => 'sometimes|integer',
            'comment' => 'sometimes|string'
        ];
    }
}
