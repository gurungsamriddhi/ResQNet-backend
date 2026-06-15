<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'type' => 'required|in:checkup,treatment,surgery,injury,observation,other',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'record_date' => 'required|date',
            'next_visit_date' => 'nullable|date|after_or_equal:record_date',
        ];
    }
}
