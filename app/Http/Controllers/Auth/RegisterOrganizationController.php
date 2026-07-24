<?php

namespace App\Http\Controllers\Auth;

use App\Events\OrganizationRegisteredEvent;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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

        // Fix: Convert empty strings for phone numbers to actual NULLs 
        // so they don't break MySQL unique constraints.
        if ($request->has('user_phone') && empty($request->user_phone)) {
            $request->merge(['user_phone' => null]);
        }

        $request->validate([
            // User validations
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'user_phone' => 'nullable|string|max:20|unique:users,phone',

            // Organization validations
            'org_name' => 'required|string|max:255',
            'org_email' => 'required|string|email|max:255|unique:organizations,email',
            'registration_number' => 'required|string|max:255',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ],
            [
                // --- Custom Error Messages ---
                'user_email.unique' => 'This email address is already registered to a user account.',
                'user_phone.unique' => 'This phone number is already in use by another user.',
                'org_email.unique'  => 'An organization with this email address is already registered.',
                'password.confirmed' => 'The password confirmation does not match.',
                'document.max'       => 'The registration document cannot be larger than 5MB.',
                'document.mimes'     => 'The document must be a PDF, JPG, JPEG, or PNG file.',
            ]
        );

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

            // Fix: Generate a truly unique slug by checking the DB (handled natively by Laravel helper if needed, or loop)
            $slug = Str::slug($request->org_name);
            if (Organization::where('slug', $slug)->exists()) {
                $slug = $slug . '-' . Str::lower(Str::random(6));
            }


            // 2. Create the Organization (pending)
            $org = Organization::create([
                'user_id' => $user->id,
                'name' => $request->org_name,
                'slug' => $slug,
                'email' => $request->org_email,
                'registration_number' => $request->registration_number,
                'status' => 'pending',
                'is_verified' => false,
            ]);

            // 3. Assign Role safely after records are drafted
            if (method_exists($user, 'assignRole')) {
                $user->assignRole('organization');
            }

            // Set organization_id on the owner user
            $user->update(['organization_id' => $org->id]);

            DB::commit();

            if ($request->hasFile('document')) {
                try {
                    $org->addMediaFromRequest('document')->toMediaCollection('registration_documents');
                } catch (\Exception $mediaException) {
                    Log::error('Media upload failed for Org ID ' . $org->id . ': ' . $mediaException->getMessage());
                    return redirect()->route('registration.pending')->with('warning', 'Registration saved, but document upload failed. Please re-upload in dashboard.');
                }
            }

            OrganizationRegisteredEvent::dispatch($org);

            return redirect()->route('registration.pending');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            // Catch MySQL Error Code 1062 (Duplicate Entry) just in case
            if ($e->errorInfo[1] == 1062) {
                return back()
                    ->withErrors(['error' => 'An organization with this name, email, or slug already exists. Please try different details.'])
                    ->withInput();
            }

            // Fallback for any other database errors (e.g., connection lost)
            return back()
                ->withErrors(['error' => 'We encountered a database issue while setting up your account. Please try again shortly.'])
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Organization Registration Exception: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            // Fallback for general application errors
            return back()
                ->withErrors(['error' => 'Something went wrong on our end. Please try again later.'])
                ->withInput();
        }
    }
}
