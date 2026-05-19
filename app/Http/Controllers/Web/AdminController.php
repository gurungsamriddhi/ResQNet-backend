<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard
     */
    public function dashboard()
    {
        $pendingOrganizations = Organization::with('user')->where('status', 'pending')->get();
        return view('admin.dashboard', compact('pendingOrganizations'));
    }

    /**
     * Approve an organization
     */
    public function approveOrganization(Organization $organization)
    {
        $organization->update([
            'status' => 'active',
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        $organization->user->update([
            'is_active' => true,
        ]);

        // Optional: Send approval email to $organization->user->email

        return redirect()->back()->with('status', 'Organization approved successfully.');
    }

    /**
     * Reject an organization
     */
    public function rejectOrganization(Organization $organization)
    {
        $organization->update([
            'status' => 'rejected',
        ]);

        // Optional: Send rejection email

        return redirect()->back()->with('status', 'Organization rejected.');
    }
}
