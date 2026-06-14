<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdoptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pet_id' => 'required|exists:pets,id',
            'message' => 'nullable|string|max:2000',
            'living_situation' => 'nullable|string|max:255',
            'has_other_pets' => 'nullable|boolean',
        ];
    }
}
