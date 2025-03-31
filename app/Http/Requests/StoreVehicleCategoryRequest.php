<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleCategoryRequest extends FormRequest
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
            'user_id' => ['required', 'numeric'],
            'business_category' => ['required', 'string', 'max:255'],
            'car' => ['required', 'numeric', 'max:1'],
            'bus' => ['required', 'numeric', 'max:1'],
            'truck' => ['required', 'numeric', 'max:1'],
        ];
    }
}
