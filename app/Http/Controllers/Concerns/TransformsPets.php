<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Pet;

trait TransformsPets
{
    protected function transformPet(Pet $pet): array
    {
        $pet->loadMissing(['primaryImage', 'images', 'organization']);

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
            'primary_image' => $pet->primaryImage?->image_url,
            'images' => $pet->images->sortBy('sort_order')
                ->map(fn ($img) => ['id' => $img->id, 'url' => $img->image_url])
                ->values()
                ->toArray(),
        ];
    }
}
