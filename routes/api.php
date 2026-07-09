<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\AdoptionRequestController;
use App\Http\Controllers\Api\RescueController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\UserDashboardController;
use App\Http\Controllers\Api\SavedPetController;
use App\Http\Controllers\Api\UserProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Base URL: http://yourapp.test/api/
*/

// Broadcasting auth endpoint (used by mobile app to authenticate private channels)
Route::post('/broadcasting/auth', function (Request $request) {
    $request->validate([
        'channel_name' => 'required|string',
        'socket_id' => 'required|string',
    ]);

    return Illuminate\Support\Facades\Broadcast::auth($request);
})->middleware(['auth:sanctum']);

Route::prefix('v1')->group(function () {   // Optional but recommended: versioning

    // ====================== PUBLIC ROUTES ======================

    Route::prefix('auth')->name('api.auth.')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');
        Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend-otp');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
    });

    // Public Pet Routes
    Route::prefix('pets')->name('api.pets.')->group(function () {
        Route::get('/', [PetController::class, 'index'])->name('index');
        Route::get('/{pet}', [PetController::class, 'show'])->name('show');
        Route::get('/qr/{qrCodeId}', [PetController::class, 'showByQr'])->name('qr.show');
    });

    // ====================== PROTECTED ROUTES ======================

    Route::middleware(['auth:sanctum'])->group(function () {

        // Auth Protected
        Route::prefix('auth')->name('api.auth.')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
            Route::post('/change-password', [AuthController::class, 'changePassword']);
            Route::post('/update-profile', [AuthController::class, 'updateProfile']);
        });

        // Apply 'is_active' middleware to all user-specific actions
        Route::middleware(['is_active'])->group(function () {

            // User Dashboard (account-level)
            Route::prefix('user')->name('api.user.')->group(function () {
                Route::get('/', [UserDashboardController::class, 'show'])->name('show');
                Route::patch('/', [UserDashboardController::class, 'update'])->name('update');
                Route::post('/fcm-token', [UserDashboardController::class, 'updateFcmToken'])->name('fcm-token');
            });

            // Adoption Profile (screening details — separate from account)
            Route::prefix('user/profile')->name('api.user-profile.')->group(function () {
                Route::get('/', [UserProfileController::class, 'show'])->name('show');
                Route::post('/', [UserProfileController::class, 'storeOrUpdate'])->name('store-update');
            });

            // Saved Pets (Favorites)
            Route::prefix('saved-pets')->name('api.saved-pets.')->group(function () {
                Route::get('/', [SavedPetController::class, 'index'])->name('index');
                Route::post('/{pet}', [SavedPetController::class, 'toggle'])->name('toggle');
            });

            // Adoptions
            Route::prefix('adoptions')->name('api.adoptions.')->group(function () {
                Route::get('/', [AdoptionRequestController::class, 'index'])->name('index');
                Route::post('/', [AdoptionRequestController::class, 'store'])->name('store');
                Route::get('/{adoption}', [AdoptionRequestController::class, 'show'])->name('show');
            });

            // Rescue Reports
            Route::prefix('rescues')->name('api.rescues.')->group(function () {
                Route::get('/', [RescueController::class, 'index'])->name('index');
                Route::post('/', [RescueController::class, 'store'])->name('store');
                Route::get('/{report}', [RescueController::class, 'show'])->name('show');
                Route::delete('/{rescue}', [RescueController::class, 'destroy'])->name('destroy'); // Add this line 
            });

            // Donations
            Route::prefix('donations')->name('api.donations.')->group(function () {
                Route::get('/', [DonationController::class, 'index'])->name('index');
                Route::post('/', [DonationController::class, 'store'])->name('store');
            });
        });
    });
});
