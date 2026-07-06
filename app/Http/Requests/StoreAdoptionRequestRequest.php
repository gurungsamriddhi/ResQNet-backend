<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdoptionRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pet_id' => 'required|exists:pets,id',
            'message' => 'required|string|max:2000',
            'living_situation' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'pet_id.exists' => 'The selected pet does not exist.',
            'message.required' => 'Please tell us why you want to adopt this pet.',
        ];
    }
}
