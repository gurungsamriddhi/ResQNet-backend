<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Notifications\SendOtpNotification;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' =>[
                'required',
                'string',
                'confirmed',
                PasswordRule::min(8)
                    ->letters()        // must contain uppercase & lowercase
                    ->mixedCase()      // at least one uppercase and one lowercase
                    ->numbers()        // at least one number
                    ->symbols()        // at least one special character
                    ->uncompromised(), // not in leaked password database
            ],
            'phone' => 'nullable|string|max:20|unique:users,phone',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => true, // Public users are active by default
        ]);

        $user->assignRole('public_user');

        $user->sendOtp('email');

        return response()->json([
            'status'=>'success',
            'message' => 'Registration successful. Please verify your email OTP.',
            'user' => $user->only(['id','name','email','phone']),
            
        ], 201);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json([
            'status'=>'error',
            'message' => 'Your account is suspended or inactive.'
            ], 403);
        }

        if(is_null($user->email_verified_at)){
            return response()->json([
                'status'=>'error',
                'message'=>'Please verify your email address first.'],
                403);
        }

        $user->update(['last_login_at' => now()]);
        
        
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'status'=>'success',
            'message' => 'Login successful',
            'user' => $user->only(['id','name','email','phone','avatar','address']),
            'token' => $token
        ]);
    }

    //Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email'=>'required|email|exists:users,email',
            'otp'=>'required|string|size:6',
        ]);

        $user = User::where('email',$request->email)->first();

        if(!$user){
            return response()->json([
                'status'=>'error',
                'message'=>'User not found.']
                ,404);
        }

        if($user->email_verified_at){
            return response()->json([
                'status'=>'success',
                 'message'=>'Email is already verified.']
                 );
        }

        $isValid = $user->verifyOtp($request->otp,'email');

        if (!$isValid){
            return response()->json([
                'status'=>'error',
                'message'=>'Invalid or expired OTP.Please request a new one.']
                ,400);
        }
 
        $user->markEmailAsVerified();
        $user->update(['last_login_at' => now()]);
        

     return response()->json([
        'status'=>'success',
        'message'=>'Email verified successfully.'
     ]);

    }

   

    // ====================== LOGOUT ======================
    public function logout(Request $request)
    {
        // Delete the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }

    // ====================== RESEND OTP ======================
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'type'  => 'sometimes|string|in:email,reset_password'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $type = $request->type ?? 'email';

        try {
            $user->sendOtp($type);
            return response()->json(['status' => 'success', 'message' => 'OTP resent successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 429);
        }
    }

    // ====================== FORGOT PASSWORD ======================
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
            ]);
        
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // For security, don't reveal if user exists, just return success
            return response()->json([
                'status' => 'success',
                'message' => 'If your email is registered, you will receive an OTP.'
                ]);
        }

        try {
            $user->sendOtp('reset_password');
            return response()->json([
                'status' => 'success',
                 'message' => 'Password reset OTP sent to your email'
                 ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error', 
                'message' => $e->getMessage()], 429);
        }
    }

    // ====================== RESET PASSWORD ======================
    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp'      => 'required|string|size:6',
            'email'    => 'required|email',
            'password' => [
                'required',
                'string',
                'confirmed',
                PasswordRule::min(8)
                    ->letters()        // must contain letters
                    ->mixedCase()      // at least one uppercase and one lowercase
                    ->numbers()        // at least one number
                    ->symbols()        // at least one special character
                    ->uncompromised(), // not in leaked password database
            ],
        ],
        [
                'password.confirmed' => 'Password confirmation does not match.'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error', 
                'message' => 'User not found'], 404);
        }

        if (!$user->verifyOtp($request->otp, 'reset_password')) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Invalid or expired OTP. Please request a new one.'], 400);
        }

        $user->forceFill(['password' => Hash::make($request->password)])->save();
        
        // Security best practice: invalidate all existing tokens since password changed
        $user->tokens()->delete();

        return response()->json([
            'status' => 'success', 
            'message' => 'Password reset successful. Please login with your new password.'
            ]);
    }

    // ====================== UPDATE PROFILE ======================
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'     => 'sometimes|string|max:255',
            'phone'    => 'sometimes|string|max:20|unique:users,phone,' . $user->id,
            'address'  => 'sometimes|nullable|string',
            'avatar'   => 'sometimes|image|mimes:jpg,jpeg,png|max:5120',
            'password' => 'required|string', // Current password confirmation
        ]);

        // Verify current password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Current password is incorrect.'], 403);
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->update($request->only(['name', 'phone', 'address']));

        return response()->json([
            'status'  => 'success',
            'message' => 'Profile updated successfully.',
            'user'    => $user->fresh()->only(['id','name','email','phone','avatar','address'])
        ]);
    }
}
