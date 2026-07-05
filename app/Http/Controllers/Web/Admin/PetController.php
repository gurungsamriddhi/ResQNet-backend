<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\TransformsPets;
use App\Models\AdoptionRequest;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    use TransformsPets;

    public function index()
    {
        $pets = Pet::with(['media', 'organization'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn ($pet) => $this->transformPet($pet));

        $canEdit = false;

        return view('shared.pets.index', compact('pets', 'canEdit'));
    }

    public function show(Pet $pet)
    {
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

        $canEdit = false;
        $isSuperAdmin = true;

        // Analytics data for the analytics tab
        $analytics = $this->buildAnalytics($pet);

        $vets = \App\Models\Vet::where('status', 'active')->orderBy('name')->get();

        return view('shared.pets.show', compact('pet', 'canEdit', 'isSuperAdmin', 'analytics', 'vets'));
    }

    /**
     * Suspend a pet (Super Admin only).
     * Sets status to 'suspended' and stores the reason.
     */
    public function suspend(Request $request, Pet $pet)
    {
        $request->validate([
            'suspension_reason' => 'required|string|max:1000',
        ]);

        if ($pet->isSuspended()) {
            return back()->with('error', 'This pet is already suspended.');
        }

        $pet->update([
            'status' => 'suspended',
            'suspension_reason' => $request->suspension_reason,
        ]);

        return back()->with('success', "{$pet->name} has been suspended.");
    }

    /**
     * Reactivate a suspended pet (Super Admin only).
     * Restores status to 'available' and clears the suspension reason.
     */
    public function reactivate(Pet $pet)
    {
        if (!$pet->isSuspended()) {
            return back()->with('error', 'This pet is not currently suspended.');
        }

        $pet->update([
            'status' => 'available',
            'suspension_reason' => null,
        ]);

        return back()->with('success', "{$pet->name} has been reactivated.");
    }

    /**
     * Build analytics data for the pet show page.
     */
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
