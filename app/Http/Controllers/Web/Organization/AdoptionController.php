<?php

namespace App\Http\Controllers\Web\Organization;

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

        $liveListings = Pet::with(['primaryImage'])
            ->where('organization_id', $orgId)
            ->where('status', 'available')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($pet) => $this->transformListing($pet));

        $applications = AdoptionRequest::with(['pet.primaryImage', 'user'])
            ->whereHas('pet', fn ($q) => $q->where('organization_id', $orgId))
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($req) => $this->transformApplication($req));

        $successfulRehomings = Pet::where('organization_id', $orgId)
            ->where('status', 'adopted')
            ->count();

        return view('admin.adoptions.index', compact('liveListings', 'applications', 'successfulRehomings'));
    }

    public function approve(Request $request, AdoptionRequest $adoption)
    {
        $this->authorizeOrgPet($adoption);

        if ($adoption->status !== 'pending') {
            return response()->json(['message' => 'This application has already been reviewed.'], 422);
        }

        DB::transaction(function () use ($adoption, $request) {
            $adoption->update([
                'status' => 'approved',
                'organization_response' => $request->input('organization_response'),
                'reviewed_at' => now(),
            ]);

            $adoption->pet->update([
                'status' => 'adopted',
                'adopted_at' => now(),
            ]);

            AdoptionRequest::where('pet_id', $adoption->pet_id)
                ->where('id', '!=', $adoption->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'admin_notes' => 'Another application was approved.',
                    'reviewed_at' => now(),
                ]);
        });

        $adoption->load(['pet.primaryImage', 'user']);

        return response()->json([
            'message' => 'Adoption application approved.',
            'application' => new AdoptionRequestResource($adoption),
        ]);
    }

    public function reject(Request $request, AdoptionRequest $adoption)
    {
        $this->authorizeOrgPet($adoption);

        if ($adoption->status !== 'pending') {
            return response()->json(['message' => 'This application has already been reviewed.'], 422);
        }

        $request->validate(['organization_response' => 'nullable|string']);

        $adoption->update([
            'status' => 'rejected',
            'organization_response' => $request->input('organization_response'),
            'reviewed_at' => now(),
        ]);

        $adoption->load(['pet.primaryImage', 'user']);

        return response()->json([
            'message' => 'Adoption application rejected.',
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
            'weight' => '-',
            'bio' => $pet->description ?? '',
            'traits' => array_filter([$pet->species, $pet->size, $pet->gender]),
            'primary_image' => $pet->primaryImage?->image_url,
            'healthChecklist' => [
                ['label' => 'Fully Vaccinated', 'done' => $pet->is_vaccinated],
                ['label' => 'Neutered', 'done' => $pet->is_neutered],
            ],
        ];
    }

    public function updateStatus(Request $request, AdoptionRequest $adoption)
    {
        $request->validate([
            'status' => 'required|in:under_review,approved,rejected,completed',
            'organization_response' => 'nullable|string|max:1000'
        ]);

        // Build status changes array
        $updateData = [
            'status' => $request->status,
            'organization_response' => $request->organization_response,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ];

        // Specific structural timestamps handled on completion
        if ($request->status === 'completed') {
            $updateData['adopted_at'] = now();

            // Flip the Pet status flag automatically when adoption completes
            $adoption->pet()->update(['status' => 'adopted']);
        }

        $adoption->update($updateData);
        $adoption->load('pet');

        // Fire the dynamic unified notification system
        $adoption->user->notify(new AdoptionStatusUpdatedNotification($adoption));

        return response()->json([
            'message' => "Application moved to status: " . str_replace('_', ' ', $request->status),
            'adoption' => $adoption
        ]);
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
            'date' => $req->created_at?->format('Y-m-d'),
            'status' => ucfirst($req->status),
            'has_other_pets' => $req->user?->profile?->has_other_pets,
            'housing_type' => $req->user?->profile?->housing_type,
            'employment_status' => $req->user?->profile?->employment_status,
            'family_members_count' => $req->user?->profile?->family_members_count,
            'experience_with_pets' => $req->user?->profile?->experience_with_pets,
        ];
    }
}
