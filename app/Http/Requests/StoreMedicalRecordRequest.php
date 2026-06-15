<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pet_id' => 'required|exists:pets,id',
            'vet_id' => 'nullable|exists:vets,id',
            'vet_name' => 'nullable|string|max:255',
            'type' => 'required|in:checkup,treatment,surgery,injury,observation,other',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'record_date' => 'required|date',
            'next_visit_date' => 'nullable|date|after_or_equal:record_date',
        ];
    }
}
