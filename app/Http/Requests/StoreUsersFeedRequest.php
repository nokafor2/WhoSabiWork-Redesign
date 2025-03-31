<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsersFeedRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'phone_number' => ['sometimes', 'required', 'string', 'min:11', 'max:15', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'message_subject' => ['required', 'string', 'max:250'],
            'message_content' => ['required', 'string', 'max:250'],
        ];
    }
}
