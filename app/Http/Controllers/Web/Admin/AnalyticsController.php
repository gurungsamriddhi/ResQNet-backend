<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdoptionRequest;
use App\Models\Donation;
use App\Models\Organization;
use App\Models\Pet;
use App\Models\RescueReport;
use App\Models\User;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $totalOrganizations = Organization::count();
        $totalUsers = User::count();
        $totalPets = Pet::count();

        $totalDonationsAmount = Donation::where('status', 'completed')->sum('amount');
        $totalDonationsCount = Donation::where('status', 'completed')->count();

        $petsBySpecies = Pet::selectRaw('species, count(*) as count')
            ->groupBy('species')->pluck('count', 'species');

        $petsByStatus = Pet::selectRaw('status, count(*) as count')
            ->groupBy('status')->pluck('count', 'status');

        $totalAdopted = Pet::where('status', 'adopted')->count();
        $availablePets = Pet::where('status', 'available')->count();

        $adoptionRequestsByStatus = AdoptionRequest::selectRaw('status, count(*) as count')
            ->groupBy('status')->pluck('count', 'status');

        $totalAdoptionRequests = AdoptionRequest::count();
        $completedAdoptions = AdoptionRequest::where('status', 'completed')->count();
        $adoptionSuccessRate = $totalAdoptionRequests > 0
            ? round(($completedAdoptions / $totalAdoptionRequests) * 100)
            : 0;

        $rescueReportsByStatus = RescueReport::selectRaw('status, count(*) as count')
            ->groupBy('status')->pluck('count', 'status');

        $totalRescueReports = RescueReport::count();
        $resolvedRescues = RescueReport::where('status', 'resolved')->count();

        $rescueResolutionRate = $totalRescueReports > 0
            ? round(($resolvedRescues / $totalRescueReports) * 100)
            : 0;

        $donationsPerOrg = Organization::withSum(['donations' => function ($q) {
            $q->where('status', 'completed');
        }], 'amount')->get()->map(fn ($o) => [
            'name' => $o->name,
            'total' => (float) ($o->donations_sum_amount ?? 0),
        ]);

        $petsPerOrg = Organization::withCount('pets')->get()->map(fn ($o) => [
            'name' => $o->name,
            'count' => $o->pets_count,
        ]);

        $adoptionsPerOrg = Organization::withCount(['pets' => function ($q) {
            $q->where('status', 'adopted');
        }])->get()->map(fn ($o) => [
            'name' => $o->name,
            'count' => $o->pets_count,
        ]);

        $recentDonations = Donation::with('user', 'organization')
            ->latest()->take(10)->get();

        $recentRescues = RescueReport::with('user', 'organization')
            ->latest()->take(10)->get();

        return view('admin.analytics.index', compact(
            'totalOrganizations',
            'totalUsers',
            'totalPets',
            'totalDonationsAmount',
            'totalDonationsCount',
            'petsBySpecies',
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
            'donationsPerOrg',
            'petsPerOrg',
            'adoptionsPerOrg',
            'recentDonations',
            'recentRescues',
        ));
    }
}
