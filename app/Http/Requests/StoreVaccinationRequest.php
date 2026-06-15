<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVaccinationRequest extends FormRequest
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
            'vet_name' => 'nullable|string|max:255',
            'vaccine_name' => 'required|string|max:255',
            'batch_number' => 'nullable|string|max:100',
            'date_administered' => 'required|date',
            'next_due_date' => 'nullable|date|after_or_equal:date_administered',
            'notes' => 'nullable|string',
        ];
    }
}
