<?php

namespace App\Http\Controllers\Api;

use App\Events\NewAdoptionRequestEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdoptionRequest;
use App\Http\Resources\AdoptionRequestResource;
use App\Models\AdoptionRequest;
use App\Models\Pet;
use Illuminate\Http\Request;
use App\Notifications\AdoptionStatusUpdatedNotification;
use App\Notifications\NewAdoptionRequestNotification;

class AdoptionRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = AdoptionRequest::with(['pet.media', 'pet.organization'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(min((int) $request->get('per_page', 15), 50));

        return AdoptionRequestResource::collection($requests);
    }

    public function store(StoreAdoptionRequest $request)
    {
        $user = $request->user();
        $pet = Pet::findOrFail($request->pet_id);

        // Rule 1: Pet Availability Check
        if ($pet->status !== 'available') {
            return response()->json(['message' => 'This pet is no longer available.'], 422);
        }

        // Rule 2: Strict Duplicate Check (1 request per user per pet)
        $hasApplied = AdoptionRequest::where('pet_id', $pet->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review'])
            ->exists();

        if ($hasApplied) {
            return response()->json(['message' => 'You already have an active application running for this specific pet.'], 422);
        }

        // Rule 3: Platform Limit Check (Max 3 parallel applications)
        $activeRequestsCount = AdoptionRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review'])
            ->count();

        if ($activeRequestsCount >= 3) {
            return response()->json([
                'message' => 'Your previous requests are currently under review. Please wait a few days for their outcome before applying for more pets.'
            ], 422);
        }

        // Rule 4: Handle Profile Automation (Save once, pull automatically next time)
        $profileData = $request->only([
            'housing_type',
            'employment_status',
            'family_members_count',
            'has_other_pets',
            'experience_with_pets',
            'has_yard',
            'is_allergic'
        ]);

        // If data is provided, update or create their permanent profile details
        if (!empty(array_filter($profileData))) {
            $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);
        }

        // Rule 5: Build Request — profile data lives in user_profiles, not duplicated here
        $adoption = AdoptionRequest::create([
            'pet_id' => $pet->id,
            'user_id' => $user->id,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        // Rule 6: Async Notifications & Lifecycle updates
        // Dispatching notification (triggers push notifications and emails gracefully)
        $adoption->pet->organization->notify(new NewAdoptionRequestNotification($adoption));

        // Broadcast real-time event to the organization dashboard
        broadcast(new NewAdoptionRequestEvent($adoption));

        $adoption->load(['pet.media', 'pet.organization']);

        return (new AdoptionRequestResource($adoption))
            ->additional(['message' => 'Adoption request submitted successfully!'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, AdoptionRequest $adoption)
    {
        if ($adoption->user_id !== $request->user()->id) {
            abort(403);
        }

        $adoption->load(['pet.media', 'pet.organization']);

        return new AdoptionRequestResource($adoption);
    }
}
