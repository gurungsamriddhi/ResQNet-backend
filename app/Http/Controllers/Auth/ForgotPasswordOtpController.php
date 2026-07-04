<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Exception;

class ForgotPasswordOtpController extends Controller
{
    // ==========================================
    // ⚙️ SYSTEM ADMIN RESET PASSWORD METHODS
    // ==========================================

    /**
     * Show admin forgot password form.
     */
    public function showAdminForgotPasswordForm(): View
    {
        return view('auth.admin-forgot-password');
    }

    /**
     * Send OTP to Admin's email.
     */
    public function sendAdminOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if user exists and is active and has super_admin role
        if (!$user || !$user->hasRole('super_admin') || !$user->is_active) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'We could not find an admin account with that email address.']);
        }

        try {
            $user->sendOtp('reset_password');
        } catch (Exception $e) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => $e->getMessage()]);
        }

        // Store email in session to verify OTP
        session(['admin_reset_email' => $user->email]);

        return redirect()->route('admin.password.verify')
            ->with('status', 'A 6-digit OTP code has been sent to your email address.');
    }

    /**
     * Show admin OTP verification form.
     */
    public function showAdminVerifyOtpForm(): View|RedirectResponse
    {
        if (!session('admin_reset_email')) {
            return redirect()->route('admin.password.request')
                ->withErrors(['email' => 'Please enter your email address first.']);
        }

        return view('auth.admin-verify-otp', ['email' => session('admin_reset_email')]);
    }

    /**
     * Verify Admin's OTP.
     */
    public function verifyAdminOtp(Request $request): RedirectResponse
    {
        $email = session('admin_reset_email');
        if (!$email) {
            return redirect()->route('admin.password.request')
                ->withErrors(['email' => 'Please enter your email address first.']);
        }

        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('admin.password.request')
                ->withErrors(['email' => 'User not found.']);
        }

        if ($user->verifyOtp($request->otp, 'reset_password')) {
            session(['admin_reset_email_verified' => $user->email]);
            return redirect()->route('admin.password.reset')
                ->with('status', 'OTP verified successfully. You can now reset your password.');
        }

        return back()->withErrors(['otp' => 'The entered OTP code is incorrect or has expired.']);
    }

    /**
     * Show admin reset password form.
     */
    public function showAdminResetPasswordForm(): View|RedirectResponse
    {
        if (!session('admin_reset_email_verified')) {
            return redirect()->route('admin.password.request')
                ->withErrors(['email' => 'Please verify your OTP code first.']);
        }

        return view('auth.admin-reset-password');
    }

    /**
     * Reset Admin's password.
     */
    public function resetAdminPassword(Request $request): RedirectResponse
    {
        $email = session('admin_reset_email_verified');
        if (!$email) {
            return redirect()->route('admin.password.request')
                ->withErrors(['email' => 'Please verify your OTP code first.']);
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('admin.password.request')
                ->withErrors(['email' => 'User not found.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session data
        session()->forget(['admin_reset_email', 'admin_reset_email_verified']);

        return redirect()->route('admin.login')
            ->with('status', 'Your password has been successfully reset. You can now sign in.');
    }

    // ==========================================
    // 🏥 ORGANIZATION RESET PASSWORD METHODS
    // ==========================================

    /**
     * Show organization forgot password form.
     */
    public function showOrgForgotPasswordForm(): View
    {
        return view('auth.organization-forgot-password');
    }

    /**
     * Send OTP to Organization's email.
     */
    public function sendOrgOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if user exists and has organization role
        if (!$user || !$user->hasRole('organization') || !$user->is_active) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'We could not find an organization account with that email address.']);
        }

        try {
            $user->sendOtp('reset_password');
        } catch (Exception $e) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => $e->getMessage()]);
        }

        // Store email in session to verify OTP
        session(['org_reset_email' => $user->email]);

        return redirect()->route('organization.password.verify')
            ->with('status', 'A 6-digit OTP code has been sent to your email address.');
    }

    /**
     * Show organization OTP verification form.
     */
    public function showOrgVerifyOtpForm(): View|RedirectResponse
    {
        if (!session('org_reset_email')) {
            return redirect()->route('organization.password.request')
                ->withErrors(['email' => 'Please enter your email address first.']);
        }

        return view('auth.organization-verify-otp', ['email' => session('org_reset_email')]);
    }

    /**
     * Verify Organization's OTP.
     */
    public function verifyOrgOtp(Request $request): RedirectResponse
    {
        $email = session('org_reset_email');
        if (!$email) {
            return redirect()->route('organization.password.request')
                ->withErrors(['email' => 'Please enter your email address first.']);
        }

        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('organization.password.request')
                ->withErrors(['email' => 'User not found.']);
        }

        if ($user->verifyOtp($request->otp, 'reset_password')) {
            session(['org_reset_email_verified' => $user->email]);
            return redirect()->route('organization.password.reset')
                ->with('status', 'OTP verified successfully. You can now reset your password.');
        }

        return back()->withErrors(['otp' => 'The entered OTP code is incorrect or has expired.']);
    }

    /**
     * Show organization reset password form.
     */
    public function showOrgResetPasswordForm(): View|RedirectResponse
    {
        if (!session('org_reset_email_verified')) {
            return redirect()->route('organization.password.request')
                ->withErrors(['email' => 'Please verify your OTP code first.']);
        }

        return view('auth.organization-reset-password');
    }

    /**
     * Reset Organization's password.
     */
    public function resetOrgPassword(Request $request): RedirectResponse
    {
        $email = session('org_reset_email_verified');
        if (!$email) {
            return redirect()->route('organization.password.request')
                ->withErrors(['email' => 'Please verify your OTP code first.']);
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('organization.password.request')
                ->withErrors(['email' => 'User not found.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session data
        session()->forget(['org_reset_email', 'org_reset_email_verified']);

        return redirect()->route('organization.login')
            ->with('status', 'Your password has been successfully reset. You can now sign in.');
    }
}
