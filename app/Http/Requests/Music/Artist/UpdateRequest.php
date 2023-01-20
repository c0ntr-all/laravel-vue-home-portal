<?php

namespace App\Http\Requests\Music\Artist;

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
            'id' => 'required|integer',
            'name' => 'required|string',
            'content' => 'sometimes|string|nullable',
            'tags' => 'sometimes|array',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        ];
    }
}
