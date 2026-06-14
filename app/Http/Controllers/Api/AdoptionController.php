<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdoptionRequest;
use App\Http\Resources\AdoptionRequestResource;
use App\Models\AdoptionRequest;
use App\Models\Pet;
use Illuminate\Http\Request;

class AdoptionController extends Controller
{
    public function index(Request $request)
    {
        $requests = AdoptionRequest::with(['pet.primaryImage', 'pet.organization'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(min((int) $request->get('per_page', 15), 50));

        return AdoptionRequestResource::collection($requests);
    }

    public function store(StoreAdoptionRequest $request)
    {
        $pet = Pet::findOrFail($request->pet_id);

        if ($pet->status !== 'available') {
            return response()->json(['message' => 'This pet is not available for adoption.'], 422);
        }

        $existing = AdoptionRequest::where('pet_id', $pet->id)
            ->where('user_id', $request->user()->id)
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return response()->json(['message' => 'You already have a pending application for this pet.'], 422);
        }

        $adoption = AdoptionRequest::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
            'status' => 'pending',
        ]);

        $adoption->load(['pet.primaryImage', 'pet.organization']);

        return (new AdoptionRequestResource($adoption))
            ->additional(['message' => 'Adoption request submitted successfully.'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, AdoptionRequest $adoption)
    {
        if ($adoption->user_id !== $request->user()->id) {
            abort(403);
        }

        $adoption->load(['pet.primaryImage', 'pet.organization', 'pet.images']);

        return new AdoptionRequestResource($adoption);
    }
}
