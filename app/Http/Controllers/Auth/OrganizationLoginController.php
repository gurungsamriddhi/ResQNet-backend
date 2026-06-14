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

           
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is not active. Please contact the administrator.']);
            }

            
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

        
        return redirect()->route('organization.login')
            ->with('status', 'You have been successfully logged out.');
    }
}
