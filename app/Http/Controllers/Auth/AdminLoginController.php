<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            
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

        // Login failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
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