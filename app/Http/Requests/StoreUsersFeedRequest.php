<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $message_subject = ['suggestion', 'complain', 'request', 'other'];
        
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'phone_number' => ['sometimes', 'required', 'string', 'min:11', 'max:15', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'message_subject' => ['required', 'string', 'max:250', Rule::in($message_subject)],
            'message_content' => ['required', 'string', 'max:250'],
        ];
    }
}
