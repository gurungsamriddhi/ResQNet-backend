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
    //method called when an authenticated user wants to see a list of their adoption applications
    public function index(Request $request)
    {
        //fetches all the pet details,images annd shelter details in one call queries to adoptionrequest model
        $requests = AdoptionRequest::with(['pet.media', 'pet.organization'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('created_at')
            ->paginate(min((int) $request->get('per_page', 15), 50));

            //converts the db records into a clean, standardized json  format using api resource controller
        return AdoptionRequestResource::collection($requests);
    }

    //handles user hitting "apply for adoption"
    public function store(StoreAdoptionRequest $request)
    {
        $user = $request->user();
        $pet = Pet::findOrFail($request->pet_id);

        // Rule 1: Profile Completeness Gate
        // The mobile app should have already collected this via UserProfileController,
        // but we enforce it server-side as a safety net.
        $profile = $user->profile;
        $requiredFields = ['housing_type', 'employment_status', 'family_members_count', 'experience_with_pets'];

        if (!$profile || collect($requiredFields)->contains(fn ($f) => empty($profile->$f))) {
            return response()->json([
                'message' => 'Please complete your adoption profile before applying.',
                'redirect' => 'user_profile_form',
            ], 422);
        }

        // Rule 2: Pet Availability Check
        if ($pet->status !== 'available') {
            return response()->json(['message' => 'This pet is no longer available.'], 422);
        }

        // Rule 3: Strict Duplicate Check (1 request per user per pet)
        $hasApplied = AdoptionRequest::where('pet_id', $pet->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review'])
            ->exists();

        if ($hasApplied) {
            return response()->json(['message' => 'You already have an active application running for this specific pet.'], 422);
        }

        // Rule 4: Platform Limit Check (Max 3 parallel applications)
        $activeRequestsCount = AdoptionRequest::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'under_review'])
            ->count();

        if ($activeRequestsCount >= 3) {
            return response()->json([
                'message' => 'Your previous requests are currently under review. Please wait a few days for their outcome before applying for more pets.'
            ], 422);
        }

        // Rule 5: Build Request — profile data lives in user_profiles, not duplicated here
        $adoption = AdoptionRequest::create([
            'pet_id' => $pet->id,
            'user_id' => $user->id,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        // Dispatching notification (triggers push notifications and emails gracefully)
        $adoption->pet->organization->notify(new NewAdoptionRequestNotification($adoption));
        $adoption->pet->organization->notify(new \App\Notifications\OrganizationAlertNotification(
            'adoption',
            'New Adoption Request',
            "{$user->name} applied to adopt {$pet->name}.",
            "/organization/adoptions"
        ));

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

    public function destroy(Request $request, AdoptionRequest $adoption)
    {
        // Rule 1: Security Check - Ensure the user owns this specific request
        if ($adoption->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        // Rule 2: Status Check - Only allow cancellation before a final decision is made
        if (!in_array($adoption->status, ['pending', 'under_review'])) {
            return response()->json([
                'message' => 'You cannot cancel an application that is already ' . $adoption->status . '.'
            ], 422);
        }

        // Action: Delete the request 
        // (Since you have a 'deleted_at' column, this will soft-delete it)
        $adoption->delete();

        return response()->json([
            'message' => 'Your adoption request has been successfully cancelled.'
        ], 200);
    }
}
