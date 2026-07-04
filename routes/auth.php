<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\OrganizationLoginController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ForgotPasswordOtpController;
use Illuminate\Support\Facades\Route;

// =========================================================================
// ⚙️ 1. SYSTEM ADMIN PORTAL ROUTING
// =========================================================================
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest Authentication Sub-Group
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'create'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'store'])->name('login.submit');

        // Contextual Admin Forgot Password
        Route::get('/forgot-password', [ForgotPasswordOtpController::class, 'showAdminForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordOtpController::class, 'sendAdminOtp'])->name('password.email');

        // Contextual Admin OTP Verification
        Route::get('/verify-otp', [ForgotPasswordOtpController::class, 'showAdminVerifyOtpForm'])->name('password.verify');
        Route::post('/verify-otp', [ForgotPasswordOtpController::class, 'verifyAdminOtp'])->name('password.verify.submit');

        // Contextual Admin Reset Password Links
        Route::get('/reset-password', [ForgotPasswordOtpController::class, 'showAdminResetPasswordForm'])->name('password.reset');
        Route::post('/reset-password', [ForgotPasswordOtpController::class, 'resetAdminPassword'])->name('password.store');
    });

    // Authenticated Admin Sub-Group
    Route::middleware(['auth', 'is_active', 'role:super_admin'])->group(function () {
        Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');

        // Settings-driven password modification inside the Admin Dashboard
        Route::put('/password/update', [PasswordController::class, 'updateAdminPassword'])->name('password.update');
    });
});

// =========================================================================
// 🏥 2. ORGANIZATION PORTAL ROUTING
// =========================================================================
Route::prefix('organization')->name('organization.')->group(function () {

    // Guest Authentication Sub-Group
    Route::middleware('guest')->group(function () {
        Route::get('/login', [OrganizationLoginController::class, 'create'])->name('login');
        Route::post('/login', [OrganizationLoginController::class, 'store'])->name('login.submit');

        // Contextual Organization Forgot Password
        Route::get('/forgot-password', [ForgotPasswordOtpController::class, 'showOrgForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [ForgotPasswordOtpController::class, 'sendOrgOtp'])->name('password.email');

        // Contextual Organization OTP Verification
        Route::get('/verify-otp', [ForgotPasswordOtpController::class, 'showOrgVerifyOtpForm'])->name('password.verify');
        Route::post('/verify-otp', [ForgotPasswordOtpController::class, 'verifyOrgOtp'])->name('password.verify.submit');

        // Contextual Organization Reset Password Links
        Route::get('/reset-password', [ForgotPasswordOtpController::class, 'showOrgResetPasswordForm'])->name('password.reset');
        Route::post('/reset-password', [ForgotPasswordOtpController::class, 'resetOrgPassword'])->name('password.store');
    });

    // Authenticated Organization Sub-Group
    Route::middleware(['auth', 'is_active', 'role:organization'])->group(function () {
        Route::post('/logout', [OrganizationLoginController::class, 'destroy'])->name('logout');

        // Profile-driven password modification inside the Organization settings page
        Route::put('/password/update', [PasswordController::class, 'updateOrgPassword'])->name('password.update');
    });
});
