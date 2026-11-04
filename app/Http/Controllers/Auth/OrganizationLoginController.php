<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationLoginController extends Controller
{
    public function create()
    {
        return view('auth.organization-login');
    }

    public function store(LoginRequest $request)
    {
        // Validates, allows 5 failed attempts per email + IP, then logs in or throws
        $request->authenticate();

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

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        
        return redirect()->route('organization.login')
            ->with('status', 'You have been successfully logged out.');
    }
}
