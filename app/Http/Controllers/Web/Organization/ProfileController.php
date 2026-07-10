<?php

namespace App\Http\Controllers\Web\Organization;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $organization = auth()->user()->organization;
        return view('organization.profile.edit', compact('organization'));
    }

    public function update(\App\Http\Requests\UpdateOrganizationProfileRequest $request)
    {
        try {
            $organization = auth()->user()->organization;

            $organization->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'description' => $request->description,
                'registration_number' => $request->registration_number,
                'email' => $request->email,
            ]);

            if ($request->hasFile('logo')) {
                $organization->addMediaFromRequest('logo')->toMediaCollection('logo');
            }

            return back()->with('success', 'Organization profile updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function changePassword(\App\Http\Requests\UpdateOrganizationPasswordRequest $request)
    {
        try {
            $user = auth()->user();
            $key = 'password-change-attempts:' . $user->id;

            // Check if they've exceeded the attempt limit
            if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 3)) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('password.request')->with('error', 'Too many failed password attempts. For your security, you have been logged out. Please reset your password.');
            }

            // Verify current password
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                \Illuminate\Support\Facades\RateLimiter::hit($key, 300); // Block for 5 minutes
                $attemptsLeft = 3 - \Illuminate\Support\Facades\RateLimiter::attempts($key);
                
                if ($attemptsLeft <= 0) {
                    auth()->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('password.request')->with('error', 'Too many failed password attempts. For your security, you have been logged out. Please reset your password.');
                }

                return back()->withErrors(['current_password' => 'The provided password does not match your current password. Attempts left: ' . $attemptsLeft]);
            }

            // Clear the rate limiter on success
            \Illuminate\Support\Facades\RateLimiter::clear($key);

            $user->update([
                'password' => bcrypt($request->new_password),
            ]);

            return back()->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update password: ' . $e->getMessage());
        }
    }
}
