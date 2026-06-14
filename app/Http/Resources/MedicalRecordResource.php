<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalRecordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pet_id' => $this->pet_id,
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description,
            'notes' => $this->notes,
            'record_date' => $this->record_date?->format('Y-m-d'),
            'next_visit_date' => $this->next_visit_date?->format('Y-m-d'),
            'vet' => $this->whenLoaded('vet', fn () => $this->vet ? [
                'id' => $this->vet->id,
                'name' => $this->vet->name,
            ] : null),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
