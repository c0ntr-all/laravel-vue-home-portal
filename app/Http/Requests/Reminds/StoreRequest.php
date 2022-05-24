<?php

namespace App\Http\Requests\Reminds;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'title' => 'required|string|max:70',
            'content' => 'sometimes|string|max:1000',
            'datetime' => 'required|string',
            'is_active' => 'sometimes|bool'
        ];
    }
}
