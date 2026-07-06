<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'amount' => 'required|numeric|min:1|max:9999999.99',
            'payment_method' => 'required|string|in:khalti,esewa',
            'transaction_id' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min' => 'Donation must be at least NPR 1.',
            'amount.max' => 'Donation amount is too large.',
            'payment_method.in' => 'Please select a valid payment method.',
        ];
    }
}
