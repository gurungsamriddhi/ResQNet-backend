<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\AdoptionController;
use App\Http\Controllers\Api\RescueController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\UserController;

// Publicly accessible API routes
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
});


Route::get('/pets', [PetController::class, 'index']);
Route::get('/pets/qr/{qrCodeId}', [PetController::class, 'showByQr']);
Route::get('/pets/{pet}', [PetController::class, 'show']);

// Protected API routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // The 'is_active' middleware ensures suspended/pending users get a 403 response
    Route::middleware(['is_active'])->group(function () {
        // User Profile
        Route::get('/user', [UserController::class, 'show']);
        Route::patch('/user', [UserController::class, 'update']);

        // Adoptions
        Route::get('/adoptions', [AdoptionController::class, 'index']);
        Route::post('/adoptions', [AdoptionController::class, 'store']);
        Route::get('/adoptions/{adoption}', [AdoptionController::class, 'show']);

        // Rescues
        Route::get('/rescues', [RescueController::class, 'index']);
        Route::post('/rescues', [RescueController::class, 'store']);
        Route::get('/rescues/{report}', [RescueController::class, 'show']);

        // Donations
        Route::get('/donations', [DonationController::class, 'index']);
        Route::post('/donations', [DonationController::class, 'store']);
    });
});
