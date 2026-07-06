<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdoptionStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|string|in:pending,under_review,approved,rejected,completed',
            'organization_response' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Invalid adoption status.',
        ];
    }
}
