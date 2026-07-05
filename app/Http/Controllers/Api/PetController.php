<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PetResource;
use App\Models\AdoptionRequest;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'species' => 'nullable|in:dog,cat,bird',
            'size' => 'nullable|in:small,medium,large',
            'gender' => 'nullable|in:male,female',
            'organization_id' => 'nullable|integer|exists:organizations,id',
            'per_page' => 'nullable|integer|min:1|max:50',
            'search' => 'nullable|string|max:255',
        ]);

        $query = Pet::query()
            ->with('media', 'organization')
            ->where('status', 'available')
            ->orderByDesc('created_at');

        // Filters (consistent with validated input)
        $query->when(
            isset($validated['species']),
            fn($q) =>
            $q->where('species', $validated['species'])
        );

        $query->when(
            isset($validated['size']),
            fn($q) =>
            $q->where('size', $validated['size'])
        );

        $query->when(
            isset($validated['gender']),
            fn($q) =>
            $q->where('gender', $validated['gender'])
        );

        $query->when(
            isset($validated['organization_id']),
            fn($q) =>
            $q->where('organization_id', $validated['organization_id'])
        );

        // Search
        $query->when(isset($validated['search']), function ($q) use ($validated) {
            $search = $validated['search'];

            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('breed', 'like', "%{$search}%");
            });
        });

        $perPage = $validated['per_page'] ?? 15;

        $pets = $query->paginate($perPage);

        return PetResource::collection($pets);
    }

    public function show(Request $request, Pet $pet)
    {
        $this->loadPetForApi($request, $pet);

        return (new PetResource($pet))->additional([
            'meta' => [
                'can_view_detailed_records' =>
                $this->canViewDetailedRecords($request, $pet),
            ],
        ]);
    }

    public function showByQr(Request $request, string $qrCodeId)
    {
        $pet = Pet::where('qr_code_id', $qrCodeId)->firstOrFail();

        $this->loadPetForApi($request, $pet);

        return (new PetResource($pet))->additional([
            'meta' => [
                'can_view_detailed_records' =>
                $this->canViewDetailedRecords($request, $pet),
            ],
        ]);
    }

    private function loadPetForApi(Request $request, Pet $pet): void
    {
        $relations = [
            'media',
            'organization',
        ];

        if ($this->canViewDetailedRecords($request, $pet)) {
            $relations = array_merge($relations, [
                'medicalRecords.vet',
                'vaccinations.vet',
                'medications.vet',
            ]);
        }

        $pet->load($relations);
    }

    private function canViewDetailedRecords(Request $request, Pet $pet): bool
    {
        $user = $request->user('sanctum');

        if (!$user) {
            return false;
        }

        return AdoptionRequest::where('pet_id', $pet->id)
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->exists();
    }
}
