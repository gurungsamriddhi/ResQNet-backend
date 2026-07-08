<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use App\Models\OrganizationTeam;
use Illuminate\Http\Request;

class OrganizationTeamController extends Controller
{
    public function index()
    {
        $orgId = auth()->user()->organization_id;

        $teamMembers = OrganizationTeam::where('organization_id', $orgId)
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'contact' => $member->phone ?? 'N/A',
                    'status' => $member->status === 'active' ? 'Active' : 'Inactive',
                    'availability' => $member->status === 'active' ? 'Available' : 'Unavailable',
                    'appliedAt' => $member->created_at->diffForHumans(),
                    'skills' => ['Available for Dispatch'],
                    'sector' => 'Main Sector',
                    'emergencyContact' => 'N/A',
                    'availabilityDetails' => 'Ready for task assignment',
                    'bio' => 'Organization team member available for rescue dispatch.',
                    'missions' => [],
                ];
            });

        return view('organization.organizationteam.index', compact('teamMembers'));
    }

    public function invite(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:organization_teams,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|string',
        ]);

        $organizationId = auth()->user()->organization_id;

        $member = OrganizationTeam::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'organization_id' => $organizationId,
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Volunteer added successfully!',
            'user' => [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
                'contact' => $member->phone ?? 'N/A',
                'status' => 'Active',
                'availability' => 'Available',
                'appliedAt' => $member->created_at->diffForHumans(),
                'skills' => ['Available for Dispatch'],
                'sector' => 'Main Sector',
                'emergencyContact' => 'N/A',
                'availabilityDetails' => 'Ready for task assignment',
                'bio' => 'Organization team member available for rescue dispatch.',
                'missions' => [],
            ],
        ]);
    }

    public function remove(OrganizationTeam $user)
    {
        // the parameter is named $user but it's an OrganizationTeam model
        $member = $user;
        
        if ($member->organization_id !== auth()->user()->organization_id) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $member->delete();

        return response()->json(['message' => 'Team member removed.']);
    }
}
