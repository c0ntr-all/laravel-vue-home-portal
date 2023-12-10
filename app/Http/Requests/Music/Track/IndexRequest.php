<?php

namespace App\Http\Requests\Music\Track;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'filters' => 'required|array',
            'filters.type' => 'required|string',
            'filters.union' => 'required|boolean',
            'filters.rate' => 'required|array',
            'filters.rate.*' => 'required|string'
        ];
    }
}
