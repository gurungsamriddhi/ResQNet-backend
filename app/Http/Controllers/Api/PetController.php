<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PetResource;
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

    public function show(Pet $pet)
    {
        $pet->load(['primaryImage', 'images', 'organization', 'medicalRecords.vet', 'vaccinations.vet', 'medications.vet']);

        return new PetResource($pet);
    }

    public function showByQr(string $qrCodeId)
    {
        $pet = Pet::where('qr_code_id', $qrCodeId)
            ->with(['primaryImage', 'images', 'organization', 'medicalRecords.vet', 'vaccinations.vet', 'medications.vet'])
            ->firstOrFail();

        return new PetResource($pet);
    }
}
