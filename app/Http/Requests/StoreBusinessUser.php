<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessUser extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|min:11|max:15|unique:users',
            'address_line_1' => 'required|string|max:255',
            // 'address_line_2' => ['string', 'max:255'],
            // 'address_line_3' => ['string', 'max:255'],
            'town' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'business_name' => 'required|string|max:255',
            'business_category' => 'required|string|max:255',
        ];
    }
}
