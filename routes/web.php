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
use App\Http\Controllers\Web\PublicPetController;
use App\Http\Controllers\Web\Admin\VetController as AdminVetController;
use App\Http\Controllers\Web\Organization\VetController as OrgVetController;

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

Route::get('/pets/qr/{qrCodeId}', [PublicPetController::class, 'showByQr'])
    ->name('public.pets.qr.show');

// ====================== PROTECTED ROUTES ======================

// Super Admin Routes
Route::middleware(['auth', 'is_active', 'role:super_admin'])->prefix('admin') ->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/organizations', [OrganizationManagementController::class, 'index'])->name('organizations.index');
    Route::post('/organizations/{organization}/approve', [OrganizationManagementController::class, 'approve'])->name('organizations.approve');
    Route::post('/organizations/{organization}/reject', [OrganizationManagementController::class, 'reject'])->name('organizations.reject');
    Route::post('/organizations/{organization}/suspend', [OrganizationManagementController::class, 'suspend'])->name('organizations.suspend');
    Route::post('/organizations/{organization}/verify', [OrganizationManagementController::class, 'verify'])->name('organizations.verify');
    
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/deactivate', [UserManagementController::class, 'deactivate'])->name('users.deactivate');

    Route::get('/pets', [AdminPetController::class, 'index'])->name('pets.index');
    Route::get('/pets/{pet}', [AdminPetController::class, 'show'])->name('pets.show');
    Route::post('/pets/{pet}/suspend', [AdminPetController::class, 'suspend'])->name('pets.suspend');
    Route::post('/pets/{pet}/reactivate', [AdminPetController::class, 'reactivate'])->name('pets.reactivate');

    Route::get('/health', function () {
        return view('admin.health.index');
    })->name('health.index');

    Route::resource('vets', AdminVetController::class);

    Route::get('/settings', function () {
        return view('admin.settings.index');
    })->name('settings.index');
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
    Route::post('/adoptions/{adoption}/approve', [AdoptionController::class, 'approve'])->name('adoptions.approve');
    Route::post('/adoptions/{adoption}/reject', [AdoptionController::class, 'reject'])->name('adoptions.reject');
    
    Route::get('/rescues', [RescueController::class, 'index'])->name('rescues.index');
    Route::post('/rescues/{report}/update', [RescueController::class, 'updateStatus'])->name('rescues.update');
    Route::post('/rescues/{report}/link', [RescueController::class, 'linkToPet'])->name('rescues.link');
    
    Route::get('/health', [HealthController::class, 'index'])->name('health.index');
    Route::post('/health/medical-records', [HealthController::class, 'storeMedicalRecord'])->name('health.medical-records.store');
    Route::put('/health/medical-records/{record}', [HealthController::class, 'updateMedicalRecord'])->name('health.medical-records.update');
    Route::delete('/health/medical-records/{record}', [HealthController::class, 'destroyMedicalRecord'])->name('health.medical-records.destroy');
    Route::post('/health/vaccinations', [HealthController::class, 'storeVaccination'])->name('health.vaccinations.store');
    Route::delete('/health/vaccinations/{vaccination}', [HealthController::class, 'destroyVaccination'])->name('health.vaccinations.destroy');
    Route::post('/health/medications', [HealthController::class, 'storeMedication'])->name('health.medications.store');
    Route::delete('/health/medications/{medication}', [HealthController::class, 'destroyMedication'])->name('health.medications.destroy');
    
    Route::apiResource('vets', OrgVetController::class);

    Route::get('/settings', function () {
        return view('organization.settings.index');
    })->name('settings.index');
    
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('users.index');
    Route::get('/volunteers', function () {
        return view('admin.volunteers.index');
    })->name('volunteers.index');
    Route::get('/analytics', function () {
        return view('admin.analytics.index');
    })->name('analytics.index');
    Route::get('/staff', [\App\Http\Controllers\Web\Organization\StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff/invite', [\App\Http\Controllers\Web\Organization\StaffController::class, 'invite'])->name('staff.invite');
    Route::delete('/staff/{user}', [\App\Http\Controllers\Web\Organization\StaffController::class, 'remove'])->name('staff.remove');

    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    
    Route::get('/profile', [OrgProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [OrgProfileController::class, 'update'])->name('profile.update');
});
// Fallback login route to prevent "Route [login] not defined" errors
Route::get('/login', function () {
    return redirect()->route('organization.login');
})->name('login');

require __DIR__ . '/auth.php';
