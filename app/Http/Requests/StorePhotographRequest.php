<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePhotographRequest extends FormRequest
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
            'filename' => ['required', 'string', 'max:50'],
            'path' => ['required', 'string', 'max:255'],
            'size' => ['required', 'numeric',],
            'caption' => ['sometimes', 'string', 'max:255'],
            'photo_type' => ['required', 'string', 'max:15'],
            'visible' => ['required', 'string', 'max:255'],
        ];
    }
}
