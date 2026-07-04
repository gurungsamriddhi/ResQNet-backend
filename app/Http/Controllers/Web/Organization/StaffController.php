<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    /**
     * Display a list of all staff members in this organization.
     */
    public function index()
    {
        // Get the current organization ID
        $organizationId = auth()->user()->organization_id;

        // Fetch all users who belong to this organization
        $staffMembers = User::where('organization_id', $organizationId)->paginate(10);

        return view('organization.staff.index', compact('staffMembers'));
    }

    /**
     * Invite a new staff member to the organization.
     */
    public function invite(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:organization,staff,vet', // You can expand these roles in Spatie later
        ]);

        $organizationId = auth()->user()->organization_id;

        // Create the new staff user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(12)), // Give them a random password for now
            'organization_id' => $organizationId,
            'is_active' => true, // Staff are active immediately
        ]);

        // Assign their specific role using Spatie
        $user->assignRole($request->role);

        // TODO: In a real app, you would send a Password Reset email here 
        // so the new staff member can set their own password!

        return back()->with('success', 'Staff member invited successfully! They can log in with their email. (Don\'t forget to setup email notifications!)');
    }

    /**
     * Remove a staff member from the organization.
     */
    public function remove(User $user)
    {
        // Ensure they are only removing people from their OWN organization
        if ($user->organization_id !== auth()->user()->organization_id) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent them from deleting themselves
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot remove yourself!']);
        }

        $user->delete();

        return back()->with('success', 'Staff member removed.');
    }
}
