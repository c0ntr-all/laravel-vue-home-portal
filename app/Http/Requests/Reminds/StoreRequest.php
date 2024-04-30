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
            'group_id' => 'sometimes|numeric|exists:reminds_groups,id',
            'title' => 'required|string|max:70',
            'content' => 'sometimes|string|max:1000|nullable',
            'datetime' => 'required|date_format:Y-m-d H:i',
            'is_active' => 'sometimes|bool',
            'is_regular' => 'sometimes|bool',
            'interval' => 'sometimes|string',
        ];
    }
}
