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
        $query = Pet::with(['primaryImage', 'images', 'organization'])
            ->where('status', 'available');

        if ($request->filled('species')) {
            $query->where('species', $request->species);
        }

        if ($request->filled('size')) {
            $query->where('size', $request->size);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('breed', 'like', "%{$search}%");
            });
        }

        $pets = $query->orderByDesc('created_at')->paginate(
            min((int) $request->get('per_page', 15), 50)
        );

        return PetResource::collection($pets);
    }

    public function show(Request $request, Pet $pet)
    {
        $this->loadPetForApi($request, $pet);

        return (new PetResource($pet))->additional([
            'meta' => [
                'can_view_detailed_records' => $this->canViewDetailedRecords($request, $pet),
            ],
        ]);
    }

    public function showByQr(Request $request, string $qrCodeId)
    {
        $pet = Pet::where('qr_code_id', $qrCodeId)
            ->firstOrFail();

        $this->loadPetForApi($request, $pet);

        return (new PetResource($pet))->additional([
            'meta' => [
                'can_view_detailed_records' => $this->canViewDetailedRecords($request, $pet),
            ],
        ]);
    }

    private function loadPetForApi(Request $request, Pet $pet): void
    {
        $relations = ['primaryImage', 'images', 'organization'];

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
