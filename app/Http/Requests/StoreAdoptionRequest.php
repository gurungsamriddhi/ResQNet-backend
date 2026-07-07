<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdoptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for submitting an adoption application.
     *
     * Profile screening data is now managed separately via UserProfileController.
     * The AdoptionRequestController enforces profile completeness server-side
     * before this validation even runs.
     */
    public function rules(): array
    {
        return [
            'pet_id'  => 'required|exists:pets,id',
            'message' => 'required|string|max:1000',
        ];
    }
}
