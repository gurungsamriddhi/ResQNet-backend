<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdoptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'housing_type' => $this->housing_type ? strtolower($this->housing_type) : null,
            'employment_status' => $this->employment_status ? strtolower($this->employment_status) : null,
            'experience_with_pets' => $this->experience_with_pets ? strtolower($this->experience_with_pets) : null,
        ]);
    }

    public function rules(): array
    {
        $user = $this->user();
        // Check if the user is missing a profile or hasn't filled out mandatory fields
        $needsProfile = !$user->profile || is_null($user->profile->housing_type);

        return [
            'pet_id' => 'required|exists:pets,id',
            'message' => 'required|string|max:1000',

            // Onboarding Profile validation is only enforced if they haven't filled it out yet
            'housing_type' => $needsProfile ? 'required|in:apartment,house,condo,other' : 'nullable',
            'employment_status' => $needsProfile ? 'required|in:employed,unemployed,student,retired' : 'nullable',
            'family_members_count' => $needsProfile ? 'required|integer|min:1' : 'nullable',
            'has_other_pets' => $needsProfile ? 'required|boolean' : 'nullable',
            'experience_with_pets' => $needsProfile
                ? 'required|in:none,beginner,intermediate,experienced'
                : 'nullable',
            'has_yard' => 'nullable|boolean',
            'is_allergic' => 'nullable|boolean',
        ];
    }
}
