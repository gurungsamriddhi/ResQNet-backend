<?php

namespace App\Http\Controllers\Web\Organization;

use App\Events\AdoptionStatusUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\TransformsPets;
use App\Http\Resources\AdoptionRequestResource;
use App\Models\AdoptionRequest;
use App\Notifications\AdoptionStatusUpdatedNotification;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdoptionController extends Controller
{
    use TransformsPets;

    public function index()
    {
        $orgId = auth()->user()?->organization?->id;

        $liveListings = Pet::with(['media', 'organization'])
            ->where('organization_id', $orgId)
            ->where('status', 'available')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($pet) => $this->transformListing($pet));

        // eager-load user.profile to prevent N+1 queries in transformApplication()
        $applications = AdoptionRequest::with(['pet.media', 'user.profile', 'pet.organization'])
            ->whereHas('pet', fn ($q) => $q->where('organization_id', $orgId))
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($req) => $this->transformApplication($req));

        $successfulRehomings = Pet::where('organization_id', $orgId)
            ->where('status', 'adopted')
            ->count();

        return view('admin.adoptions.index', compact('liveListings', 'applications', 'successfulRehomings', 'orgId'));
    }

    public function updateStatus(Request $request, AdoptionRequest $adoption)
    {
        $this->authorizeOrgPet($adoption);

        $request->validate([
            'status' => 'required|in:under_review,approved,rejected,completed',
            'organization_response' => 'nullable|string|max:1000',
        ]);

        if ($adoption->status !== 'pending') {
            return response()->json(['message' => 'This application has already been reviewed.'], 422);
        }

        $autoRejectedApplications = collect();

        DB::transaction(function () use ($request, $adoption, &$autoRejectedApplications) {
            $adoption->update([
                'status' => $request->status,
                'organization_response' => $request->organization_response,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            if ($request->status === 'approved') {
                $adoption->pet->update([
                    'status' => 'adopted',
                    'adopted_at' => now(),
                ]);

                // Get other pending applications for auto-rejection
                $autoRejectedApplications = AdoptionRequest::with(['pet', 'user'])
                    ->where('pet_id', $adoption->pet_id)
                    ->where('id', '!=', $adoption->id)
                    ->where('status', 'pending')
                    ->get();

                // Auto reject them
                AdoptionRequest::where('pet_id', $adoption->pet_id)
                    ->where('id', '!=', $adoption->id)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'rejected',
                        'organization_response' => 'Another application was approved.',
                        'reviewed_at' => now(),
                        'reviewed_by' => auth()->id(),
                    ]);
            }
        });

        // === After Transaction ===

        // Refresh the main adoption with relationships
        $adoption->load(['pet.media', 'user']);

        // Notify primary applicant
        $adoption->user->notify(new AdoptionStatusUpdatedNotification($adoption));
        broadcast(new AdoptionStatusUpdatedEvent($adoption));

        // Notify auto-rejected users
        foreach ($autoRejectedApplications as $rejected) {
            // Important: Refresh each one because status was updated in bulk
            $rejected->refresh();
            $rejected->load(['pet', 'user']);

            $rejected->user->notify(new AdoptionStatusUpdatedNotification($rejected));
            broadcast(new AdoptionStatusUpdatedEvent($rejected));
        }

        return response()->json([
            'message' => match ($request->status) {
                'approved' => 'Adoption application approved successfully.',
                'rejected' => 'Application rejected.',
                default => 'Application status updated.',
            },
            'application' => new AdoptionRequestResource($adoption),
        ]);
    }

    private function authorizeOrgPet(AdoptionRequest $adoption): void
    {
        $orgId = auth()->user()?->organization?->id;
        if ($adoption->pet->organization_id !== $orgId) {
            abort(403);
        }
    }

    private function transformListing(Pet $pet): array
    {
        $speciesColors = [
            'dog' => 'bg-amber-500',
            'cat' => 'bg-blue-500',
            'bird' => 'bg-pink-500',
            'rabbit' => 'bg-violet-500',
            'other' => 'bg-slate-600',
        ];

        $age = $pet->age_months;
        $ageLabel = $age === null ? '-' : (
            $age < 12 ? "{$age} months" : floor($age / 12) . ' years'
        );

        return [
            'id' => $pet->id,
            'name' => $pet->name,
            'breed' => $pet->breed ?? '',
            'color' => $speciesColors[$pet->species] ?? 'bg-slate-600',
            'age' => $ageLabel,
            'bio' => $pet->description ?? '',
            'traits' => array_filter([$pet->species, $pet->size, $pet->gender]),
            'primary_image' => $pet->getFirstMediaUrl('images') ?: null,
            'org_name' => $pet->organization?->name ?? 'ResQNet Animal Shelter',
            'healthChecklist' => [
                ['label' => 'Fully Vaccinated', 'done' => $pet->is_vaccinated],
                ['label' => 'Neutered', 'done' => $pet->is_neutered],
            ],
        ];
    }

    private function transformApplication(AdoptionRequest $req): array
    {
        return [
            'id' => $req->id,
            'name' => $req->user?->name ?? 'Guest',
            'email' => $req->user?->email ?? '',
            'phone' => $req->user?->phone ?? '',
            'address' => $req->living_situation ?? '',
            'reason' => $req->message ?? '',
            'pet' => $req->pet->name,
            'pet_id' => $req->pet->id,
            'pet_age' => $req->pet->age_months === null ? '-' : ($req->pet->age_months < 12 ? "{$req->pet->age_months} months" : floor($req->pet->age_months / 12) . ' years'),
            'pet_breed' => $req->pet->breed ?? 'Unknown',
            'pet_image' => $req->pet->getFirstMediaUrl('images'),
            'pet_traits' => array_filter([$req->pet->species, $req->pet->size, $req->pet->gender]),
            'date' => $req->created_at?->format('Y-m-d'),
            'status' => ucfirst($req->status),
            'has_other_pets' => $req->user?->profile?->has_other_pets,
            'housing_type' => $req->user?->profile?->housing_type,
            'employment_status' => $req->user?->profile?->employment_status,
            'family_members_count' => $req->user?->profile?->family_members_count,
            'experience_with_pets' => $req->user?->profile?->experience_with_pets,
            'bio' => $req->user?->profile?->bio,
            'preferred_pet_type' => $req->user?->profile?->preferred_pet_type,
            'has_yard' => $req->user?->profile?->has_yard,
            'is_allergic' => $req->user?->profile?->is_allergic,
        ];
    }
}
