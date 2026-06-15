<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\OrganizationLoginController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
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
        Route::get('/forgot-password', [PasswordResetLinkController::class, 'createAdminForm'])->name('password.request');
        Route::post('/forgot-password', [PasswordResetLinkController::class, 'sendAdminResetLink'])->name('password.email');

        // Contextual Admin Reset Password Links
        Route::get('/reset-password/{token}', [NewPasswordController::class, 'createAdminForm'])->name('password.reset');
        Route::post('/reset-password', [NewPasswordController::class, 'storeAdminPassword'])->name('password.store');
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
        Route::get('/forgot-password', [PasswordResetLinkController::class, 'createOrgForm'])->name('password.request');
        Route::post('/forgot-password', [PasswordResetLinkController::class, 'sendOrgResetLink'])->name('password.email');

        // Contextual Organization Reset Password Links
        Route::get('/reset-password/{token}', [NewPasswordController::class, 'createOrgForm'])->name('password.reset');
        Route::post('/reset-password', [NewPasswordController::class, 'storeOrgPassword'])->name('password.store');
    });

    // Authenticated Organization Sub-Group
    Route::middleware(['auth', 'is_active', 'role:organization'])->group(function () {
        Route::post('/logout', [OrganizationLoginController::class, 'destroy'])->name('logout');

        // Profile-driven password modification inside the Organization settings page
        Route::put('/password/update', [PasswordController::class, 'updateOrgPassword'])->name('password.update');
    });
});
