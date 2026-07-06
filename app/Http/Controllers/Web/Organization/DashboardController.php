<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use App\Models\RescueReport;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user && $user->organization) {
            $organization = $user->organization;
            $orgId = $organization->id;

            $activeRescues = RescueReport::where('organization_id', $orgId)
                ->whereIn('status', ['assigned', 'in_progress'])
                ->count();

            $pendingRescues = RescueReport::whereNull('organization_id')
                ->where('status', 'reported')
                ->count();

            $stats = [
                'active_pets' => $organization->pets()->count(),
                'pending_adoptions' => 0,
                'active_rescues' => $activeRescues,
                'pending_rescues' => $pendingRescues,
                'pets_in_care' => $organization->pets()->where('status', 'available')->count(),
                'total_donations' => 0,
            ];
        } else {
            $stats = [
                'active_pets' => 24,
                'pending_adoptions' => 7,
                'active_rescues' => 3,
                'pending_rescues' => 2,
                'pets_in_care' => 28,
                'total_donations' => 124500,
            ];
        }

        $orgId = $user && $user->organization ? $user->organization->id : null;

        return view('organization.dashboard', compact('stats', 'orgId'));
    }
}
