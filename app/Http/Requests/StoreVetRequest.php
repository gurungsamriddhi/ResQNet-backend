<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'specialization' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The veterinarian\'s full name is required.',
            'name.max' => 'The name must not exceed 255 characters.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'The email must not exceed 255 characters.',
            'phone.max' => 'The phone number must not exceed 20 characters.',
        ];
    }
}
