<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArtisan extends FormRequest
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
            // Validating the data
            'address_line_1' => 'bail|required|max:250',
            'address_line_2' => 'max:250',
            'address_line_3' => 'max:250',
            'town' => 'required',
            'state' => 'required'
        ];
    }
}
