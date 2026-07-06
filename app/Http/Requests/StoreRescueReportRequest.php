<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRescueReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'address' => 'nullable|string|max:255',
            'urgency' => 'required|string|in:low,medium,high,critical',
            'image' => 'nullable|image|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'urgency.in' => 'Urgency must be low, medium, high, or critical.',
            'image.max' => 'Image must not exceed 5MB.',
        ];
    }
}
