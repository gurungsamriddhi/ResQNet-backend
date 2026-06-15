<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrganizationApproved;
use App\Mail\OrganizationRejected;
use App\Mail\OrganizationSuspended;

class OrganizationController extends Controller
{
    public function index()
    {
        $pendingOrganizations = Organization::with(['user', 'media'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $verifiedOrganizations = Organization::with('user')
            ->where('status', 'active')
            ->latest()
            ->get();

        $suspendedOrganizations = Organization::with('user')
            ->where('status', 'suspended')
            ->latest()
            ->get();

        $rejectedOrganizations = Organization::with('user')
            ->where('status', 'rejected')
            ->latest()
            ->get();

        return view('admin.organizations.index', compact(
            'pendingOrganizations',
            'verifiedOrganizations',
            'suspendedOrganizations',
            'rejectedOrganizations'
        ));
    }

    public function approve(Organization $organization)
    {
        DB::transaction(function () use ($organization) {
            $organization->update([
                'status' => 'active',
                'is_verified' => true,
            ]);

            $organization->user->update(['is_active' => true]);
        });

        if ($organization->user && $organization->user->email) {
            Mail::to($organization->user->email)->queue(new OrganizationApproved($organization));
        }

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization approved successfully.');
    }

    public function reject(Organization $organization, Request $request)
    {
        $request->validate([
            'reasons' => 'required|array',
            'reasons.*' => 'string',
            'other_reason' => 'nullable|string|max:500'
        ]);

        $reasonParts = $request->reasons;
        // If "Other" was checked and text provided, append it
        if (in_array('Other', $reasonParts) && $request->filled('other_reason')) {
            $reasonParts = array_filter($reasonParts, fn($val) => $val !== 'Other');
            $reasonParts[] = $request->other_reason;
        }

        $reasonString = implode("\n- ", $reasonParts);
        $reasonString = "- " . $reasonString; // Add bullet to first item

        DB::transaction(function () use ($organization, $reasonString) {
            $organization->update([
                'status' => 'rejected',
                'rejection_reason' => $reasonString,
            ]);

            // Deactivate user
            if ($organization->user) {
                $organization->user->update(['is_active' => false]);
            }
        });

        if ($organization->user && $organization->user->email) {
            Mail::to($organization->user->email)->queue(new OrganizationRejected($organization, $reasonString));
        }

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization rejected successfully.');
    }

    public function suspend(Organization $organization, Request $request)
    {
        $request->validate([
            'reasons' => 'required|array',
            'reasons.*' => 'string',
            'other_reason' => 'nullable|string|max:500'
        ]);

        $reasonParts = $request->reasons;
        if (in_array('Other', $reasonParts) && $request->filled('other_reason')) {
            $reasonParts = array_filter($reasonParts, fn($val) => $val !== 'Other');
            $reasonParts[] = $request->other_reason;
        }

        $reasonString = implode("\n- ", $reasonParts);
        $reasonString = "- " . $reasonString;

        $organization->update([
            'status' => 'suspended',
            'suspension_reason' => $reasonString,
        ]);

        if ($organization->user) {
            $organization->user->update(['is_active' => false]);
        }

        if ($organization->user && $organization->user->email) {
            Mail::to($organization->user->email)->queue(new OrganizationSuspended($organization, $reasonString));
        }

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization has been suspended.');
    }

    public function verify(Organization $organization)
    {
        $organization->update(['is_verified' => true]);

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization verified with badge.');
    }
}
