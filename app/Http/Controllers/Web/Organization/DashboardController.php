<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user && $user->organization) {
            $organization = $user->organization;
            $stats = [
                'active_pets' => $organization->pets()->count(),
                'pending_adoptions' => 0,
                'active_rescues' => 0,
                'pets_in_care' => $organization->pets()->where('status', 'available')->count(),
                'total_donations' => 0,
            ];
        } else {
            $stats = [
                'active_pets' => 24,
                'pending_adoptions' => 7,
                'active_rescues' => 3,
                'pets_in_care' => 28,
                'total_donations' => 124500,
            ];
        }

        return view('admin.dashboard', compact('stats'));
    }
}