<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterOrganizationController extends Controller
{
    /**
     * Show the organization registration form.
     */
    public function create()
    {
        return view('auth.register-organization');
    }

    /**
     * Handle Organization Registration.
     */
    public function store(Request $request)
    {
        //     dd([
        //     'password' => $request->password,
        //     'password_confirmation' => $request->password_confirmation,
        //     'all_fields' => $request->all()
        // ]);
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
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();

        try {
            // 1. Create the User (inactive — requires admin approval)
            $user = User::create([
                'name' => $request->user_name,
                'email' => $request->user_email,
                'password' => Hash::make($request->password),
                'phone' => $request->user_phone,
                'is_active' => false,
            ]);

            $user->assignRole('organization');

            // 2. Create the Organization (pending)
            $org = Organization::create([
                'user_id' => $user->id,
                'name' => $request->org_name,
                'slug' => Str::slug($request->org_name) . '-' . uniqid(),
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

            return redirect()->route('registration.pending');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])->withInput();
        }
    }
}
