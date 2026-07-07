<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Pet;
use App\Support\AppUrl;

trait TransformsPets
{
    protected function transformPet(Pet $pet): array
    {
        $pet->loadMissing('media', 'organization');

        $allMedia = $pet->getMedia('images');

        return [
            'id' => $pet->id,
            'name' => $pet->name,
            'species' => $pet->species,
            'breed' => $pet->breed,
            'age_months' => $pet->age_months,
            'gender' => $pet->gender,
            'size' => $pet->size,
            'color' => $pet->color,
            'description' => $pet->description,
            'health_status' => $pet->health_status,
            'is_vaccinated' => $pet->is_vaccinated,
            'is_neutered' => $pet->is_neutered,
            'status' => $pet->status,
            'adopted_at' => $pet->adopted_at?->toDateTimeString(),
            'qr_code_id' => $pet->qr_code_id,
            'organization' => $pet->organization?->name ?? '',
            'organization_id' => $pet->organization_id,
            'created_at' => $pet->created_at?->toDateTimeString(),
            'updated_at' => $pet->updated_at?->toDateTimeString(),
            'primary_image' => AppUrl::absolute($allMedia->first()?->getUrl()),
            'images' => $allMedia->values()->map(fn ($m) => [
                'id' => $m->id,
                'url' => AppUrl::absolute($m->getUrl()),
            ])->toArray(),
        ];
    }
}
