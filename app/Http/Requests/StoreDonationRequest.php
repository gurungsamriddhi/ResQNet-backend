<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'organization_id' => 'nullable|exists:organizations,id',
            'amount' => 'required|numeric|min:10|max:9999999.99',
            'payment_method' => [
                'required',
                'string',
                Rule::in(['upi', 'phonepe', 'gpay', 'paytm', 'netbanking', 'khalti', 'esewa']),
            ],
            'frequency' => 'required|in:one_time,monthly',
            'donor_name' => 'required_unless:is_anonymous,1,true|nullable|string|max:255',
            'donor_email' => 'required|email|max:255',
            'donor_phone' => 'nullable|string|max:20',
            'is_anonymous' => 'sometimes|boolean',
            'remarks' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min' => 'Donation must be at least NPR 10.',
            'amount.max' => 'Donation amount is too large.',
            'payment_method.in' => 'Please select a valid payment method.',
            'donor_name.required_unless' => 'Please enter your name or choose to donate anonymously.',
            'donor_email.required' => 'Email is required for acknowledgment purposes.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_anonymous' => $this->boolean('is_anonymous'),
        ]);
    }
}
