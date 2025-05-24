<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhoto extends FormRequest
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
            'caption' => ['sometimes', 'string', 'nullable', 'max:255'],
            'thumbnail' => ['image', 'mimes:jpg,jpeg,png,gif,svg', 'max:4096', 'dimensions:min_height=500'],
        ];
    }
}
