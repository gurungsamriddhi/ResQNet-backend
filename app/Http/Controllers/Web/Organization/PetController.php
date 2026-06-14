<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\TransformsPets;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Models\Pet;
use App\Models\PetImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetController extends Controller
{
    use TransformsPets;

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

        $canEdit = true;

        return view('shared.pets.index', compact('pets', 'canEdit'));
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
        $pet->load(['primaryImage', 'images', 'organization', 'adoptionRequests']);
        $canEdit = true;
        return view('shared.pets.show', compact('pet', 'canEdit'));
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
        if (isset($data['status'])) {
            if ($data['status'] === 'adopted') {
                $data['adopted_at'] = $pet->adopted_at ?? now();
            } elseif ($pet->status === 'adopted') {
                $data['adopted_at'] = null;
            }
        }
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
        $data = ['status' => $request->status];
        if ($request->status === 'adopted') {
            $data['adopted_at'] = $pet->adopted_at ?? now();
        } elseif ($pet->status === 'adopted') {
            $data['adopted_at'] = null;
        }
        $pet->update($data);
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

}
