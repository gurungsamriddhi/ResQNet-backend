<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VaccinationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pet_id' => $this->pet_id,
            'vaccine_name' => $this->vaccine_name,
            'batch_number' => $this->batch_number,
            'date_administered' => $this->date_administered?->format('Y-m-d'),
            'next_due_date' => $this->next_due_date?->format('Y-m-d'),
            'notes' => $this->notes,
            'vet' => $this->whenLoaded('vet', fn () => $this->vet ? [
                'id' => $this->vet->id,
                'name' => $this->vet->name,
            ] : null),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
