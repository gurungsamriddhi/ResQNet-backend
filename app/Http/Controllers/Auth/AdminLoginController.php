<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    /**
     * Show admin login form
     */
    public function create()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle admin login request
     */
    public function store(LoginRequest $request)
    {
        // Validates, allows 5 failed attempts per email + IP, then logs in or throws
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Only allow super_admin role
        if (!$user->hasRole('super_admin')) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'You do not have admin access.']);
        }

        // Check if account is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Your account has been deactivated.']);
        }

        // Redirect to admin dashboard
        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Logout admin user
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect back to admin login page
        return redirect()->route('admin.login')
                         ->with('status', 'You have been successfully logged out.');
    }
}