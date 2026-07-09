<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\TransformsPets;
use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    use TransformsPets;

    public function index()
    {
        $user = auth()->user();

        if ($user && $user->organization) {
            $orgId = $user->organization->id;
            $pets = Pet::with('media')
                ->where('organization_id', $orgId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(fn($pet) => $this->transformPet($pet));
        } else {
            $pets = Pet::with('media')
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

        $pet->load('media');

        return response()->json([
            'message' => 'Pet created successfully.',
            'pet' => $this->transformPet($pet),
        ], 201);
    }

    public function show(Pet $pet)
    {
        $orgId = auth()->user()?->organization?->id;
        if ($orgId && $pet->organization_id !== $orgId) {
            abort(403);
        }
        $pet->load([
            'media',
            'organization',
            'creator',
            'adoptionRequests.user',
            'medicalRecords.vet',
            'vaccinations.vet',
            'medications.vet',
            'rescueReports',
        ]);
        $canEdit = true;
        $isSuperAdmin = false;
        $analytics = $this->buildAnalytics($pet);

        $orgId = auth()->user()?->organization?->id;
        $vets = \App\Models\Vet::where(function ($q) use ($orgId) {
            $q->where('organization_id', $orgId)
              ->orWhereNull('organization_id');
        })->where('status', 'active')->orderBy('name')->get();

        return view('shared.pets.show', compact('pet', 'canEdit', 'isSuperAdmin', 'analytics', 'vets'));
    }

    public function edit(Pet $pet)
    {
        return redirect()->route('organization.pets.index');
    }

    public function update(UpdatePetRequest $request, Pet $pet)
    {
        $orgId = auth()->user()?->organization?->id;
        if ($orgId && $pet->organization_id !== $orgId) {
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

        // Remove specific images by media ID
        if ($request->filled('remove_image_ids')) {
            foreach ($request->remove_image_ids as $id) {
                $media = $pet->media()->find($id);
                if ($media) {
                    $media->delete();
                }
            }
        }

        // Replace primary image — delete old first, then add new
        if ($request->hasFile('primary_image')) {
            $existingPrimary = $pet->getFirstMedia('images');
            if ($existingPrimary) {
                $existingPrimary->delete();
            }
            $pet->addMedia($request->file('primary_image'))
                ->toMediaCollection('images');
        }

        // Append new gallery images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $pet->addMedia($file)->toMediaCollection('images');
            }
        }

        $pet->load('media');

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Pet updated successfully.',
                'pet' => $this->transformPet($pet),
            ]);
        }

        return redirect()->back()->with('success', 'Pet updated successfully.');
    }

    public function destroy(Pet $pet)
    {
        $orgId = auth()->user()?->organization?->id;
        if ($orgId && $pet->organization_id !== $orgId) {
            abort(403);
        }
        $petName = $pet->name;
        $pet->delete();

        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json(['message' => "{$petName} deleted successfully."]);
        }

        return redirect()->route('organization.pets.index')->with('success', "{$petName} deleted successfully.");
    }

    public function changeStatus(Request $request, Pet $pet)
    {
        $orgId = auth()->user()?->organization?->id;
        if ($orgId && $pet->organization_id !== $orgId) {
            abort(403);
        }
        
        $request->merge(['status' => strtolower(trim($request->input('status', '')))]);
        $request->validate(['status' => 'required|in:available,adopted,unavailable,inactive,in_care,suspended']);
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
        $orgId = auth()->user()?->organization?->id;
        if ($orgId && $pet->organization_id !== $orgId) {
            abort(403);
        }
        return response()->json(['qr_code_id' => $pet->qr_code_id]);
    }

    private function handleImageUploads(Request $request, Pet $pet): void
    {
        if ($request->hasFile('primary_image')) {
            $pet->addMedia($request->file('primary_image'))
                ->toMediaCollection('images');
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $pet->addMedia($file)->toMediaCollection('images');
            }
        }
    }

    private function buildAnalytics(Pet $pet): array
    {
        $totalRequests = $pet->adoptionRequests->count();
        $approvedRequests = $pet->adoptionRequests->where('status', 'approved')->count();

        return [
            'days_in_care' => (int) $pet->created_at->diffInDays(now()),
            'total_medical_records' => $pet->medicalRecords->count(),
            'total_vaccinations' => $pet->vaccinations->count(),
            'total_medications' => $pet->medications->count(),
            'adoption_requests' => $totalRequests,
            'adoption_success_rate' => $totalRequests > 0
                ? round(($approvedRequests / $totalRequests) * 100)
                : 0,
            'last_medical_visit' => $pet->medicalRecords->first()?->record_date?->format('M d, Y'),
            'next_appointment' => $pet->medicalRecords
                ->whereNotNull('next_visit_date')
                ->sortBy('next_visit_date')
                ->first()?->next_visit_date?->format('M d, Y'),
        ];
    }

}
