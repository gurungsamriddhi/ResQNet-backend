<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VolunteerController extends Controller
{
    public function index()
    {
        $orgId = auth()->user()->organization_id;

        $teamMembers = User::where('organization_id', $orgId)
            ->select(['id', 'name', 'email', 'phone', 'is_active', 'created_at'])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'contact' => $user->phone ?? 'N/A',
                    'status' => $user->is_active ? 'Active' : 'Inactive',
                    'availability' => $user->is_active ? 'Available' : 'Unavailable',
                    'appliedAt' => $user->created_at->diffForHumans(),
                    'skills' => ['Available for Dispatch'],
                    'sector' => 'Main Sector',
                    'emergencyContact' => 'N/A',
                    'availabilityDetails' => 'Ready for task assignment',
                    'bio' => 'Organization team member available for rescue dispatch.',
                    'missions' => [],
                ];
            });

        return view('organization.volunteers.index', compact('teamMembers'));
    }

    public function invite(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:staff,vet',
        ]);

        $organizationId = auth()->user()->organization_id;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make(Str::random(12)),
            'organization_id' => $organizationId,
            'is_active' => true,
        ]);

        $user->assignRole($request->role);

        return response()->json([
            'message' => 'Volunteer added successfully!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'contact' => $user->phone ?? 'N/A',
                'status' => 'Active',
                'availability' => 'Available',
                'appliedAt' => $user->created_at->diffForHumans(),
                'skills' => ['Available for Dispatch'],
                'sector' => 'Main Sector',
                'emergencyContact' => 'N/A',
                'availabilityDetails' => 'Ready for task assignment',
                'bio' => 'Organization team member available for rescue dispatch.',
                'missions' => [],
            ],
        ]);
    }

    public function remove(User $user)
    {
        if ($user->organization_id !== auth()->user()->organization_id) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'You cannot remove yourself!'], 422);
        }

        $user->delete();

        return response()->json(['message' => 'Volunteer removed.']);
    }
}
