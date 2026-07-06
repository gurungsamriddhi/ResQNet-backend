<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use App\Models\AdoptionRequest;
use App\Models\Donation;
use App\Models\Pet;
use App\Models\RescueReport;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $org = auth()->user()->organization;

        if (!$org) {
            return redirect()->back()->with('error', 'No organization associated with your account.');
        }

        $orgPetIds = $org->pets()->pluck('id');

        $totalPets = $org->pets()->count();

        $petsByStatus = $org->pets()->selectRaw('status, count(*) as count')
            ->groupBy('status')->pluck('count', 'status');

        $totalAdopted = $org->pets()->where('status', 'adopted')->count();
        $availablePets = $org->pets()->where('status', 'available')->count();

        $adoptionRequestsByStatus = AdoptionRequest::whereIn('pet_id', $orgPetIds)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')->pluck('count', 'status');

        $totalAdoptionRequests = AdoptionRequest::whereIn('pet_id', $orgPetIds)->count();
        $completedAdoptions = AdoptionRequest::whereIn('pet_id', $orgPetIds)
            ->where('status', 'completed')->count();

        $adoptionSuccessRate = $totalAdoptionRequests > 0
            ? round(($completedAdoptions / $totalAdoptionRequests) * 100)
            : 0;

        $rescueReportsByStatus = $org->rescueReports()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')->pluck('count', 'status');

        $totalRescueReports = $org->rescueReports()->count();
        $resolvedRescues = $org->rescueReports()->where('status', 'resolved')->count();

        $rescueResolutionRate = $totalRescueReports > 0
            ? round(($resolvedRescues / $totalRescueReports) * 100)
            : 0;

        $totalDonations = Donation::where('organization_id', $org->id)
            ->where('status', 'completed')->sum('amount');

        $donationCount = Donation::where('organization_id', $org->id)
            ->where('status', 'completed')->count();

        $recentAdoptions = AdoptionRequest::with('pet', 'user')
            ->whereIn('pet_id', $orgPetIds)
            ->latest()->take(10)->get();

        $recentDonations = Donation::with('user')
            ->where('organization_id', $org->id)
            ->latest()->take(10)->get();

        return view('organization.analytic.index', compact(
            'org',
            'totalPets',
            'petsByStatus',
            'totalAdopted',
            'availablePets',
            'adoptionRequestsByStatus',
            'totalAdoptionRequests',
            'completedAdoptions',
            'adoptionSuccessRate',
            'rescueReportsByStatus',
            'totalRescueReports',
            'resolvedRescues',
            'rescueResolutionRate',
            'totalDonations',
            'donationCount',
            'recentAdoptions',
            'recentDonations',
        ));
    }
}
