<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusinessCategoryRequest extends FormRequest
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
            'artisan' => ['sometimes', 'numeric', 'max:1'],
            'seller' => ['sometimes', 'numeric', 'max:1'],
            'technician' => ['sometimes', 'numeric', 'max:1'],
            'spare_part_seller' => ['sometimes', 'numeric', 'max:1'],
            'business_name' => ['required', 'string', 'max:255'],
            'business_description' => ['required', 'string', 'max:255'],
            'business_page' => ['required', 'string', 'max:255'],
            'views' => ['sometimes', 'numeric'],
            'advertise' => ['sometimes', 'boolean'],
        ];
    }
}
