<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterOrganizationController;
use App\Http\Controllers\Web\Admin\AnalyticsController;
use App\Http\Controllers\Web\PublicPetController;
use App\Http\Controllers\Web\Admin\OrganizationController;
use App\Http\Controllers\Web\Admin\UserController;
use App\Http\Controllers\Web\Admin\VolunteerController as AdminVolunteerController;
use App\Http\Controllers\Web\Admin\VetController as AdminVetController;
use App\Http\Controllers\Web\Admin\PetController as AdminPetController;
use App\Http\Controllers\Web\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Web\Admin\HealthController as AdminHealthController;
use App\Http\Controllers\Web\Organization\PetController;
use App\Http\Controllers\Web\Organization\AdoptionController;
use App\Http\Controllers\Web\Organization\RescueController;
use App\Http\Controllers\Web\Organization\FinanceController;
use App\Http\Controllers\Web\Organization\StaffController;
use App\Http\Controllers\Web\Organization\HealthController as OrgHealthController;
use App\Http\Controllers\Web\Organization\VolunteerController as OrgVolunteerController;
use App\Http\Controllers\Web\Organization\DashboardController as OrgDashboardController;
use App\Http\Controllers\Web\Organization\ProfileController as OrgProfileController;
use App\Http\Controllers\Web\Organization\SettingsController as OrgSettingsController;
use App\Http\Controllers\Web\Organization\AnalyticsController as OrgAnalyticsController;
use App\Http\Controllers\Web\Organization\VetController as OrgVetController;


// This controls http://127.0.0.1:8000/
Route::get('/', function () {
    return view('welcome');
});

// ================================================
//  PUBLIC ROUTES
// ================================================
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



// ================================================
// PROTECTED ADMIN ROUTES
// ================================================
Route::middleware(['auth', 'is_active', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    //Organization Management
    Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
    Route::post('/organizations/{organization}/approve', [OrganizationController::class, 'approve'])->name('organizations.approve');
    Route::post('/organizations/{organization}/reject', [OrganizationController::class, 'reject'])->name('organizations.reject');
    Route::post('/organizations/{organization}/suspend', [OrganizationController::class, 'suspend'])->name('organizations.suspend');
    Route::post('/organizations/{organization}/verify', [OrganizationController::class, 'verify'])->name('organizations.verify');

    //Users Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{user}/reactivate', [UserController::class, 'reactivate'])->name('users.reactivate');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    //Pets Management
    Route::get('/pets', [AdminPetController::class, 'index'])->name('pets.index');
    Route::get('/pets/{pet}', [AdminPetController::class, 'show'])->name('pets.show');
    Route::post('/pets/{pet}/suspend', [AdminPetController::class, 'suspend'])->name('pets.suspend');
    Route::post('/pets/{pet}/reactivate', [AdminPetController::class, 'reactivate'])->name('pets.reactivate');


    Route::resource('vets', AdminVetController::class);

    Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');


    Route::get('/volunteers', [AdminVolunteerController::class, 'index'])->name('volunteers.index');


    Route::get('/medical-records', [AdminHealthController::class, 'index'])->name('medical-records.index');

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
});




// ================================================
//  PROTECTED ORGANIZATION ROUTES
// ================================================
Route::middleware(['auth', 'is_active', 'role:organization'])->prefix('organization')->name('organization.')->group(function () {

    Route::get('/dashboard', [OrgDashboardController::class, 'index'])->name('dashboard');

    Route::resource('pets', PetController::class);
    Route::post('/pets/{pet}/status', [PetController::class, 'changeStatus'])->name('pets.status');
    Route::get('/pets/{pet}/qr', [PetController::class, 'generateQr'])->name('pets.qr');

    Route::get('/adoptions', [AdoptionController::class, 'index'])->name('adoptions.index');
    Route::post('/adoptions/{adoption}/status', [AdoptionController::class, 'updateStatus'])->name('adoptions.status');

    Route::get('/rescues', [RescueController::class, 'index'])->name('rescues.index');
    Route::post('/rescues/{report}/accept', [RescueController::class, 'acceptCase'])->name('rescues.accept');
    Route::post('/rescues/{report}/assign-team', [RescueController::class, 'assignTeam'])->name('rescues.assign-team');
    Route::post('/rescues/{report}/update', [RescueController::class, 'updateStatus'])->name('rescues.update');
    Route::post('/rescues/{report}/link', [RescueController::class, 'linkToPet'])->name('rescues.link');


    Route::get('/health', [OrgHealthController::class, 'index'])->name('health.index');
    Route::post('/health/medical-records', [OrgHealthController::class, 'storeMedicalRecord'])->name('health.medical-records.store');
    Route::put('/health/medical-records/{record}', [OrgHealthController::class, 'updateMedicalRecord'])->name('health.medical-records.update');
    Route::delete('/health/medical-records/{record}', [OrgHealthController::class, 'destroyMedicalRecord'])->name('health.medical-records.destroy');
    Route::put('/health/pets/{pet}/summary', [OrgHealthController::class, 'updatePetHealthSummary'])->name('health.pets.summary.update');
    Route::delete('/health/pets/{pet}/history', [OrgHealthController::class, 'clearPetMedicalHistory'])->name('health.pets.history.clear');
    Route::post('/health/vaccinations', [OrgHealthController::class, 'storeVaccination'])->name('health.vaccinations.store');
    Route::put('/health/vaccinations/{vaccination}', [OrgHealthController::class, 'updateVaccination'])->name('health.vaccinations.update');
    Route::delete('/health/vaccinations/{vaccination}', [OrgHealthController::class, 'destroyVaccination'])->name('health.vaccinations.destroy');
    Route::post('/health/medications', [OrgHealthController::class, 'storeMedication'])->name('health.medications.store');
    Route::delete('/health/medications/{medication}', [OrgHealthController::class, 'destroyMedication'])->name('health.medications.destroy');

    Route::resource('vets', OrgVetController::class);

    Route::get('/analytics', [OrgAnalyticsController::class, 'index'])->name('analytics.index');

    Route::get('/settings', [OrgSettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [OrgSettingsController::class, 'update'])->name('settings.update');
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::post('/staff/invite', [StaffController::class, 'invite'])->name('staff.invite');
    Route::delete('/staff/{user}', [StaffController::class, 'remove'])->name('staff.remove');

    Route::get('/volunteers', [OrgVolunteerController::class, 'index'])->name('volunteers.index');
    Route::post('/volunteers/invite', [OrgVolunteerController::class, 'invite'])->name('volunteers.invite');
    Route::delete('/volunteers/{user}', [OrgVolunteerController::class, 'remove'])->name('volunteers.remove');

    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');

    Route::get('/profile', [OrgProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [OrgProfileController::class, 'update'])->name('profile.update');
});

require __DIR__ . '/auth.php';
