<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.organizations.index', compact('organizations'));
    }

    public function approve(Organization $org)
    {
        DB::transaction(function () use ($org) {
            $org->update([
                'status' => 'active',
                'is_verified' => true,   // or handle separately
            ]);

            $org->user->update([
                'is_active' => true,
            ]);
        });

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization approved successfully.');
    }

    public function reject(Organization $org, Request $request)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        DB::transaction(function () use ($org, $request) {
            $org->update([
                'status' => 'rejected',
                'rejection_reason' => $request->reason,
            ]);

            // Optionally deactivate user
            $org->user->update(['is_active' => false]);
        });

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization rejected.');
    }

    public function suspend(Organization $org)
    {
        $org->update(['status' => 'suspended']);
        $org->user->update(['is_active' => false]);

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization has been suspended.');
    }

    public function verify(Organization $org)
    {
        $org->update(['is_verified' => true]);

        return redirect()->route('admin.organizations.index')
            ->with('success', 'Organization verified with badge.');
    }
}
