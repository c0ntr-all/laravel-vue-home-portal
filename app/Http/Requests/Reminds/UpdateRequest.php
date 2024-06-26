<?php

namespace App\Http\Requests\Reminds;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'title' => 'sometimes|string',
            'content' => 'sometimes|string',
            'group_id' => 'sometimes|numeric|exists:reminds_groups,id',
            'datetime' => 'sometimes|date_format:Y-m-d H:i',
            'is_active' => 'sometimes|bool',
            'is_regular' => 'sometimes|bool',
            'interval' => 'sometimes|string',
        ];
    }
}
