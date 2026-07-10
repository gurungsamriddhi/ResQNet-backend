<?php

namespace App\Http\Resources;

use App\Support\AppUrl;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'qr_code_id' => $this->qr_code_id,
            'name' => $this->name,
            'species' => $this->species,
            'breed' => $this->breed,
            'age_months' => $this->age_months,
            'gender' => $this->gender,
            'size' => $this->size,
            'color' => $this->color,
            'description' => $this->description,
            'health_status' => $this->health_status,
            'is_vaccinated' => $this->is_vaccinated,
            'is_neutered' => $this->is_neutered,
            'status' => $this->status,
            'adopted_at' => $this->adopted_at?->toIso8601String(),
            'organization' => $this->whenLoaded('organization', fn () => [
                'id' => $this->organization->id,
                'name' => $this->organization->name,
                'slug' => $this->organization->slug,
                'email' => $this->organization->email,
                'phone' => $this->organization->phone,
                'address' => $this->organization->address,
                'logo' => AppUrl::absolute($this->organization->getFirstMediaUrl('logo') ?: null),
            ]),
            'primary_image' => $this->whenLoaded('media', fn () => AppUrl::absolute($this->getFirstMediaUrl('images') ?: null)),
            'images' => $this->whenLoaded('media', fn () => $this->getMedia('images')
                ->map(fn ($m) => ['id' => $m->id, 'url' => AppUrl::absolute($m->getUrl())])
                ->values()),
            'medical_records' => MedicalRecordResource::collection($this->whenLoaded('medicalRecords')),
            'vaccinations' => VaccinationResource::collection($this->whenLoaded('vaccinations')),
            'medications' => MedicationResource::collection($this->whenLoaded('medications')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
