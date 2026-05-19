<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Web\Admin\OrganizationManagementController;
use App\Http\Controllers\Web\Admin\UserManagementController;
use App\Http\Controllers\Web\Organization\DashboardController as OrgDashboardController;
use App\Http\Controllers\Web\Organization\PetController;
use App\Http\Controllers\Web\Organization\AdoptionController;
use App\Http\Controllers\Web\Organization\RescueController;
use App\Http\Controllers\Web\Organization\HealthController;
use App\Http\Controllers\Web\Organization\FinanceController;
use App\Http\Controllers\Web\Organization\ProfileController as OrgProfileController;

Route::get('/', function () {
    return view('welcome');
});

// Organization Web Routes (Registration)
Route::get('/register-organization', [AuthController::class, 'showRegisterOrganizationForm'])->name('register.organization');
Route::post('/register-organization', [AuthController::class, 'registerOrganization']);

require __DIR__.'/auth.php';

// Super Admin Web Routes (Protected)
Route::middleware(['auth', 'is_active', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Organization Management
    Route::get('/organizations', [OrganizationManagementController::class, 'index'])->name('organizations.index');
    Route::post('/organizations/{organization}/approve', [OrganizationManagementController::class, 'approve'])->name('organizations.approve');
    Route::post('/organizations/{organization}/reject', [OrganizationManagementController::class, 'reject'])->name('organizations.reject');
    Route::post('/organizations/{organization}/suspend', [OrganizationManagementController::class, 'suspend'])->name('organizations.suspend');
    Route::post('/organizations/{organization}/verify', [OrganizationManagementController::class, 'verify'])->name('organizations.verify');
    
    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/deactivate', [UserManagementController::class, 'deactivate'])->name('users.deactivate');
});

// Organization Admin Web Routes (Protected)
Route::middleware(['auth', 'is_active', 'role:organization'])->prefix('organization')->name('organization.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [OrgDashboardController::class, 'index'])->name('dashboard');
    
    // Pet Management
    Route::resource('pets', PetController::class);
    Route::post('/pets/{pet}/status', [PetController::class, 'changeStatus'])->name('pets.status');
    Route::get('/pets/{pet}/qr', [PetController::class, 'generateQr'])->name('pets.qr');
    
    // Adoption Management
    Route::get('/adoptions', [AdoptionController::class, 'index'])->name('adoptions.index');
    Route::post('/adoptions/{request}/approve', [AdoptionController::class, 'approve'])->name('adoptions.approve');
    Route::post('/adoptions/{request}/reject', [AdoptionController::class, 'reject'])->name('adoptions.reject');
    
    // Rescue Management
    Route::get('/rescues', [RescueController::class, 'index'])->name('rescues.index');
    Route::post('/rescues/{report}/update', [RescueController::class, 'updateStatus'])->name('rescues.update');
    Route::post('/rescues/{report}/link', [RescueController::class, 'linkToPet'])->name('rescues.link');
    
    // Health Management
    Route::resource('health', HealthController::class);
    
    // Finance
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    
    // Profile
    Route::get('/profile', [OrgProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [OrgProfileController::class, 'update'])->name('profile.update');
});
