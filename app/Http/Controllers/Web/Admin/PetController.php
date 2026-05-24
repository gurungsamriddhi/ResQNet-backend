<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Models\Pet;
use App\Models\PetImage;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function index()
    {
        $pets = Pet::with(['primaryImage', 'images', 'organization'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($pet) {
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
                    'qr_code_id' => $pet->qr_code_id,
                    'organization' => $pet->organization?->name ?? '',
                    'organization_id' => $pet->organization_id,
                    'created_at' => $pet->created_at?->toDateTimeString(),
                    'updated_at' => $pet->updated_at?->toDateTimeString(),
                    'primary_image' => $pet->primaryImage?->image_url,
                    'images' => $pet->images->sortBy('sort_order')
                        ->map(fn($img) => ['id' => $img->id, 'url' => $img->image_url])
                        ->values()
                        ->toArray(),
                ];
            });

        return view('admin.pets.index', compact('pets'));
    }

    public function store(StorePetRequest $request)
    {
        $data = $request->safe()->except(['primary_image', 'images']);
        $pet = Pet::create($data);

        if ($request->hasFile('primary_image')) {
            $path = $request->file('primary_image')->store('pets/' . $pet->id, 'public');
            $pet->images()->create([
                'image_path' => $path,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('pets/' . $pet->id, 'public');
                $pet->images()->create([
                    'image_path' => $path,
                    'is_primary' => false,
                    'sort_order' => $i + 1,
                ]);
            }
        }

        $pet->load(['primaryImage', 'images', 'organization']);

        return response()->json([
            'message' => 'Pet created successfully.',
            'pet' => $this->transformPet($pet),
        ], 201);
    }

    public function update(UpdatePetRequest $request, Pet $pet)
    {
        $data = $request->safe()->except(['primary_image', 'images', 'remove_image_ids']);
        $pet->update($data);

        if ($request->hasFile('primary_image')) {
            $oldPrimary = $pet->primaryImage;
            if ($oldPrimary) {
                Storage::disk('public')->delete($oldPrimary->image_path);
                $oldPrimary->delete();
            }
            $path = $request->file('primary_image')->store('pets/' . $pet->id, 'public');
            $pet->images()->create([
                'image_path' => $path,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }

        if ($request->hasFile('images')) {
            $maxOrder = $pet->images()->where('is_primary', false)->max('sort_order') ?? 0;
            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('pets/' . $pet->id, 'public');
                $pet->images()->create([
                    'image_path' => $path,
                    'is_primary' => false,
                    'sort_order' => $maxOrder + $i + 1,
                ]);
            }
        }

        if ($request->filled('remove_image_ids')) {
            $images = PetImage::whereIn('id', $request->remove_image_ids)
                ->where('pet_id', $pet->id)
                ->get();
            foreach ($images as $img) {
                Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }
        }

        $pet->load(['primaryImage', 'images', 'organization']);

        return response()->json([
            'message' => 'Pet updated successfully.',
            'pet' => $this->transformPet($pet),
        ]);
    }

    public function destroy(Pet $pet)
    {
        $pet->delete();
        return response()->json(['message' => 'Pet deleted successfully.']);
    }

    private function transformPet($pet): array
    {
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
            'qr_code_id' => $pet->qr_code_id,
            'organization' => $pet->organization?->name ?? '',
            'organization_id' => $pet->organization_id,
            'created_at' => $pet->created_at?->toDateTimeString(),
            'updated_at' => $pet->updated_at?->toDateTimeString(),
            'primary_image' => $pet->primaryImage?->image_url,
            'images' => $pet->images->sortBy('sort_order')
                ->map(fn($img) => ['id' => $img->id, 'url' => $img->image_url])
                ->values()
                ->toArray(),
        ];
    }
}
