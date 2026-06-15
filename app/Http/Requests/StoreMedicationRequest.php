<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMedicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pet_id' => 'required|exists:pets,id',
            'vet_id' => [
                'nullable',
                Rule::exists('vets', 'id')->where(function ($query) {
                    $orgId = auth()->user()?->organization?->id;
                    $query->where('is_global', true)
                          ->orWhere(function ($q) use ($orgId) {
                              $q->where('is_global', false)
                                ->where('organization_id', $orgId);
                          });
                }),
            ],
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
        ];
    }
}
