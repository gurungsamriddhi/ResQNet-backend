<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the logged in user's organization
        $organization = auth()->user()->organization;

        // Fetch stats if the organization exists
        $stats = [
            'active_pets' => $organization ? $organization->pets()->count() : 0,
            'pending_adoptions' => 0, // We'll wire this up properly later
            'active_rescues' => 0,
            'total_donations' => 0,
        ];

        return view('organization.dashboard', compact('stats'));
    }
}
