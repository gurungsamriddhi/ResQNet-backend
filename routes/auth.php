<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\OrganizationLoginController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use Illuminate\Support\Facades\Route;

// ================================================
//  ADMIN AUTHENTICATION
// ================================================
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest Routes (Login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'create'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'store'])->name('login.submit');

        // Forgot Password
        Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');
        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');
    });

    // Password Reset Routes (outside guest middleware because user clicks link from email)
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    // Logout (must be inside auth, not guest)
    Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
});

// ================================================
//  ORGANIZATION AUTHENTICATION
// ================================================
Route::prefix('organization')->name('organization.')->group(function () {

    // Guest Routes (Login)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [OrganizationLoginController::class, 'create'])->name('login');
        Route::post('/login', [OrganizationLoginController::class, 'store'])->name('login.submit');

        // Forgot Password
        Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');
        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');
    });

    // Password Reset Routes
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    // Logout
    Route::post('/logout', [OrganizationLoginController::class, 'destroy'])->name('logout');
});

// ================================================
//  PROTECTED PASSWORD MANAGEMENT (shared)
// ================================================
Route::middleware('auth')->group(function () {

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('/password', [PasswordController::class, 'update'])
        ->name('password.update');
});
