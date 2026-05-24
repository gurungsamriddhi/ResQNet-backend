<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Models\Pet;
use App\Models\PetImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user && $user->organization) {
            $orgId = $user->organization->id;
            $pets = Pet::with(['primaryImage', 'images'])
                ->where('organization_id', $orgId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn($pet) => $this->transformPet($pet));
        } else {
            $pets = Pet::with(['primaryImage', 'images'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn($pet) => $this->transformPet($pet));
        }

        return view('admin.pets.index', compact('pets'));
    }

    public function create()
    {
        return redirect()->route('organization.pets.index');
    }

    public function store(StorePetRequest $request)
    {
        $orgId = auth()->user()?->organization?->id;
        $data = $request->safe()->except(['primary_image', 'images']);
        $data['organization_id'] = $orgId;

        $pet = Pet::create($data);

        $this->handleImageUploads($request, $pet);

        $pet->load(['primaryImage', 'images']);

        return response()->json([
            'message' => 'Pet created successfully.',
            'pet' => $this->transformPet($pet),
        ], 201);
    }

    public function show(Pet $pet)
    {
        if ($pet->organization_id !== auth()->user()?->organization?->id) {
            abort(403);
        }
        $pet->load(['primaryImage', 'images']);
        return response()->json(['pet' => $this->transformPet($pet)]);
    }

    public function edit(Pet $pet)
    {
        return redirect()->route('organization.pets.index');
    }

    public function update(UpdatePetRequest $request, Pet $pet)
    {
        if ($pet->organization_id !== auth()->user()?->organization?->id) {
            abort(403);
        }

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

        $pet->load(['primaryImage', 'images']);

        return response()->json([
            'message' => 'Pet updated successfully.',
            'pet' => $this->transformPet($pet),
        ]);
    }

    public function destroy(Pet $pet)
    {
        if ($pet->organization_id !== auth()->user()?->organization?->id) {
            abort(403);
        }
        $pet->delete();
        return response()->json(['message' => 'Pet deleted successfully.']);
    }

    public function changeStatus(Request $request, Pet $pet)
    {
        if ($pet->organization_id !== auth()->user()?->organization?->id) {
            abort(403);
        }
        $request->validate(['status' => 'required|in:available,adopted,unavailable,inactive']);
        $pet->update(['status' => $request->status]);
        return response()->json(['message' => 'Status updated.', 'pet' => $this->transformPet($pet)]);
    }

    public function generateQr(Request $request, Pet $pet)
    {
        if ($pet->organization_id !== auth()->user()?->organization?->id) {
            abort(403);
        }
        return response()->json(['qr_code_id' => $pet->qr_code_id]);
    }

    private function handleImageUploads(Request $request, Pet $pet): void
    {
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
            'organization' => auth()->user()?->organization?->name ?? '',
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
