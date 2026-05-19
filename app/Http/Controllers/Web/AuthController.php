<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Check if user is inactive
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is pending approval or inactive.',
                ]);
            }

            if (Auth::user()->hasRole('super_admin')) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show the organization registration form
     */
    public function showRegisterOrganizationForm()
    {
        return view('auth.register-organization'); // Assuming this blade template will exist
    }

    /**
     * Handle Organization Registration
     */
    public function registerOrganization(Request $request)
    {
        $request->validate([
            // User validations
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'user_phone' => 'nullable|string|max:20',

            // Organization validations
            'org_name' => 'required|string|max:255',
            'org_email' => 'required|string|email|max:255',
            'registration_number' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        DB::beginTransaction();
        try {
            // 1. Create the User (inactive)
            $user = User::create([
                'name' => $request->user_name,
                'email' => $request->user_email,
                'password' => Hash::make($request->password),
                'phone' => $request->user_phone,
                'is_active' => false, // Requires admin approval
            ]);

            $user->assignRole('organization');

            // 2. Create the Organization (pending)
            $org = Organization::create([
                'user_id' => $user->id,
                'name' => $request->org_name,
                'slug' => \Illuminate\Support\Str::slug($request->org_name) . '-' . uniqid(),
                'email' => $request->org_email,
                'registration_number' => $request->registration_number,
                'status' => 'pending',
                'is_verified' => false,
            ]);

            // 3. Handle document upload via Spatie MediaLibrary
            if ($request->hasFile('document')) {
                $org->addMediaFromRequest('document')->toMediaCollection('registration_documents');
            }

            DB::commit();

            // Redirect back with a success message
            return redirect()->route('login')->with('status', 'Your registration request has been submitted. Please wait for admin approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])->withInput();
        }
    }
}
