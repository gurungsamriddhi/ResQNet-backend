<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // BUG FIX #1: You forgot to import Auth!

class OrganizationLoginController extends Controller
{
    public function create()
    {
        return view('auth.organization-login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // BUG FIX #2: You must Auth::logout() BEFORE returning errors!
            // Otherwise the user stays logged in even though you showed an error.
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is not active. Please contact the administrator.']);
            }

            // BUG FIX #3: Use hasRole() from Spatie, not $user->role
            // $user->role doesn't exist. Spatie stores roles in a separate table.
            if (!$user->hasRole('organization')) {
                Auth::logout();
                return back()->withErrors(['email' => 'You do not have permission to log in as organization.']);
            }

            return redirect()->intended('organization/dashboard');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // BUG FIX #4: You had "redirect->route" (missing parentheses)
        // and "organizationlogin" (wrong route name — it's "organization.login")
        // and ">with" (missing arrow) and double semicolons
        return redirect()->route('organization.login')
            ->with('status', 'You have been successfully logged out.');
    }
}
