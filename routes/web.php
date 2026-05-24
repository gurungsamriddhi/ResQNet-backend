<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterOrganizationController;
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Web\Admin\OrganizationManagementController;
use App\Http\Controllers\Web\Admin\UserManagementController;
use App\Http\Controllers\Web\Admin\PetController as AdminPetController;
use App\Http\Controllers\Web\Organization\DashboardController as OrgDashboardController;
use App\Http\Controllers\Web\Organization\PetController;
use App\Http\Controllers\Web\Organization\AdoptionController;
use App\Http\Controllers\Web\Organization\RescueController;
use App\Http\Controllers\Web\Organization\HealthController;
use App\Http\Controllers\Web\Organization\FinanceController;
use App\Http\Controllers\Web\Organization\ProfileController as OrgProfileController;

// This controls http://127.0.0.1:8000/
Route::get('/', function () {
    return view('welcome'); 
});

// ====================== PUBLIC ROUTES ======================
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

Route::get('/register-organization', [RegisterOrganizationController::class, 'create'])
     ->name('register.organization');

Route::post('/register-organization', [RegisterOrganizationController::class, 'store'])
     ->name('register.organization.submit');

Route::get('/registration-pending', function () {
    return view('auth.registration-pending');
})->name('registration.pending');

// ====================== PROTECTED ROUTES ======================

// Super Admin Routes
Route::middleware(['auth', 'is_active', 'role:super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/organizations', [OrganizationManagementController::class, 'index'])->name('organizations.index');
    Route::post('/organizations/{organization}/approve', [OrganizationManagementController::class, 'approve'])->name('organizations.approve');
    Route::post('/organizations/{organization}/reject', [OrganizationManagementController::class, 'reject'])->name('organizations.reject');
    Route::post('/organizations/{organization}/suspend', [OrganizationManagementController::class, 'suspend'])->name('organizations.suspend');
    Route::post('/organizations/{organization}/verify', [OrganizationManagementController::class, 'verify'])->name('organizations.verify');
    
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/deactivate', [UserManagementController::class, 'deactivate'])->name('users.deactivate');

    Route::get('/pets', [AdminPetController::class, 'index'])->name('pets.index');
    Route::post('/pets', [AdminPetController::class, 'store'])->name('pets.store');
    Route::put('/pets/{pet}', [AdminPetController::class, 'update'])->name('pets.update');
    Route::delete('/pets/{pet}', [AdminPetController::class, 'destroy'])->name('pets.destroy');
});

// Organization Routes
Route::middleware(app()->environment('local') ? [] : ['auth', 'is_active', 'role:organization'])
    ->prefix('organization')
    ->name('organization.')
    ->group(function () {

    Route::get('/dashboard', [OrgDashboardController::class, 'index'])->name('dashboard');

    Route::resource('pets', PetController::class);
    Route::post('/pets/{pet}/status', [PetController::class, 'changeStatus'])->name('pets.status');
    Route::get('/pets/{pet}/qr', [PetController::class, 'generateQr'])->name('pets.qr');
    
    Route::get('/adoptions', [AdoptionController::class, 'index'])->name('adoptions.index');
    Route::post('/adoptions/{request}/approve', [AdoptionController::class, 'approve'])->name('adoptions.approve');
    Route::post('/adoptions/{request}/reject', [AdoptionController::class, 'reject'])->name('adoptions.reject');
    
    Route::get('/rescues', [RescueController::class, 'index'])->name('rescues.index');
    Route::post('/rescues/{report}/update', [RescueController::class, 'updateStatus'])->name('rescues.update');
    Route::post('/rescues/{report}/link', [RescueController::class, 'linkToPet'])->name('rescues.link');
    
    Route::resource('health', HealthController::class);
    
    Route::get('/staff', [\App\Http\Controllers\Web\Organization\StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff/invite', [\App\Http\Controllers\Web\Organization\StaffController::class, 'invite'])->name('staff.invite');
    Route::delete('/staff/{user}', [\App\Http\Controllers\Web\Organization\StaffController::class, 'remove'])->name('staff.remove');

    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    
    Route::get('/profile', [OrgProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [OrgProfileController::class, 'update'])->name('profile.update');
});
require __DIR__ . '/auth.php';