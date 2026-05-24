<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'species' => 'sometimes|required|in:dog,cat,bird,rabbit,other',
            'breed' => 'nullable|string|max:100',
            'age_months' => 'nullable|integer|min:0',
            'gender' => 'sometimes|required|in:male,female,unknown',
            'size' => 'sometimes|required|in:small,medium,large,xl',
            'color' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'health_status' => 'nullable|string',
            'is_vaccinated' => 'boolean',
            'is_neutered' => 'boolean',
            'status' => 'sometimes|required|in:available,adopted,unavailable,inactive',
            'primary_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'remove_image_ids' => 'nullable|array',
            'remove_image_ids.*' => 'integer|exists:pet_images,id',
        ];
    }
}
