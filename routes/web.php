<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterOrganizationController;
use App\Http\Controllers\Web\PublicPetController;
use App\Http\Controllers\Web\DonationController;
use App\Http\Controllers\Web\Admin\AnalyticsController;
use App\Http\Controllers\Web\Admin\OrganizationController;
use App\Http\Controllers\Web\Admin\UserController;
use App\Http\Controllers\Web\Admin\VolunteerController as AdminVolunteerController;
use App\Http\Controllers\Web\Admin\VetController as AdminVetController;
use App\Http\Controllers\Web\Admin\PetController as AdminPetController;
use App\Http\Controllers\Web\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Web\Admin\HealthController as AdminHealthController;
use App\Http\Controllers\Web\Organization\PetController as OrgPetController;
use App\Http\Controllers\Web\Organization\AdoptionController as OrgAdoptionController;
use App\Http\Controllers\Web\Organization\RescueController as OrgRescueController;
use App\Http\Controllers\Web\Organization\FinanceController as OrgFinanceController;
use App\Http\Controllers\Web\Organization\StaffController as OrgStaffController;
use App\Http\Controllers\Web\Organization\HealthController as OrgHealthController;
use App\Http\Controllers\Web\Organization\OrganizationTeamController as OrgTeamController;
use App\Http\Controllers\Web\Organization\DashboardController as OrgDashboardController;
use App\Http\Controllers\Web\Organization\ProfileController as OrgProfileController;
use App\Http\Controllers\Web\Organization\SettingsController as OrgSettingsController;
use App\Http\Controllers\Web\Organization\AnalyticsController as OrgAnalyticsController;
use App\Http\Controllers\Web\Organization\VetController as OrgVetController;
use App\Http\Controllers\Web\Organization\NotificationController;


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

Route::get('/donation', [DonationController::class, 'create'])->name('donate');
Route::post('/donation', [DonationController::class, 'store'])->name('donate.store');
Route::get('/donation/callback', [DonationController::class, 'khaltiCallback'])->name('donate.callback');



// ================================================
// PROTECTED ADMIN ROUTES
// ================================================
Route::middleware(['auth', 'is_active', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    //Organization Management by Admin
    Route::get('/organizations', [OrganizationController::class, 'index'])->name('organizations.index');
    Route::post('/organizations/{organization}/approve', [OrganizationController::class, 'approve'])->name('organizations.approve');
    Route::post('/organizations/{organization}/reject', [OrganizationController::class, 'reject'])->name('organizations.reject');
    Route::post('/organizations/{organization}/suspend', [OrganizationController::class, 'suspend'])->name('organizations.suspend');
    Route::post('/organizations/{organization}/verify', [OrganizationController::class, 'verify'])->name('organizations.verify');

    //Users Management by Admin
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{user}/reactivate', [UserController::class, 'reactivate'])->name('users.reactivate');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    //Pets Management by Admin
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


    //Organization Dashboard
    Route::get('/dashboard', [OrgDashboardController::class, 'index'])->name('dashboard');

    //Organization Pet Management routes
    Route::resource('pets', OrgPetController::class);
    Route::post('/pets/{pet}/status', [OrgPetController::class, 'changeStatus'])->name('pets.status');
    Route::get('/pets/{pet}/qr', [OrgPetController::class, 'generateQr'])->name('pets.qr');

    //Organization Adoption Request Management routes
    Route::get('/adoptions', [OrgAdoptionController::class, 'index'])->name('adoptions.index');
    Route::post('/adoptions/{adoption}/status', [OrgAdoptionController::class, 'updateStatus'])->name('adoptions.status');

    //Organization Rescue Report Management
    Route::get('/rescues', [OrgRescueController::class, 'index'])->name('rescues.index');
    Route::post('/rescues/{report}/accept', [OrgRescueController::class, 'acceptCase'])->name('rescues.accept');
    Route::post('/rescues/{report}/assign-team', [OrgRescueController::class, 'assignTeam'])->name('rescues.assign-team');
    Route::post('/rescues/{report}/update', [OrgRescueController::class, 'updateStatus'])->name('rescues.update');
    Route::post('/rescues/{report}/link', [OrgRescueController::class, 'linkToPet'])->name('rescues.link');
    Route::get('/rescues/{report}', [OrgRescueController::class, 'show'])->name('rescues.show');

    //Organization Health Management of pets that includes medications, vaccinations, medical records
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

    //Organization handling vets resource handles routes for crud
    Route::resource('vets', OrgVetController::class);

    //Organization analytics include total pets rescued in this month , donations made by users shown by months and years
    Route::get('/analytics', [OrgAnalyticsController::class, 'index'])->name('analytics.index');

    // Organization Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    //lets keep this minimal 
    Route::get('/settings', [OrgSettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [OrgSettingsController::class, 'update'])->name('settings.update');

    //Organization handling staffs that can access the dashboard certain funcitionalities this is a future scope where they can invite users to use the dashboard certain functions
    Route::get('/staff', [OrgStaffController::class, 'index'])->name('staff.index');
    Route::post('/staff/invite', [OrgStaffController::class, 'invite'])->name('staff.invite');
    Route::delete('/staff/{user}', [OrgStaffController::class, 'remove'])->name('staff.remove');

    //Organization creates a team member they can assign for rescues 
    Route::get('/OrgTeam', [OrgTeamController::class, 'index'])->name('orgteams.index');
    Route::post('/OrgTeam/invite', [OrgTeamController::class, 'invite'])->name('orgteams.invite');
    Route::delete('/OrgTeam/{user}', [OrgTeamController::class, 'remove'])->name('orgteams.remove');

    //Rather than finance we will have donation index that shows the donations made by musers using payment method and keep a button that sends the email saying thank you for their contribution
    Route::get('/finance', [OrgFinanceController::class, 'index'])->name('finance.index');

    //Organization profile include changing their email, passsword, name, phone number, address basically information that they were asked to put during registration
    Route::get('/profile', [OrgProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [OrgProfileController::class, 'update'])->name('profile.update');
});

//magic link for access to case details for team members do not require login
Route::get('/rescue/access/{report}/{teamMember}', 'App\\Http\\Controllers\\Web\\Organization\\TeamAccessController@accessCase')
    ->name('rescue.access')
    ->middleware('signed');
//signed middleware is built directly into laravel.,It works by checking a digital signature (a hash) attached to the end of the URL to ensure the link has not been tampered with or modified by anyone.
Route::post('/rescue/arrived/{report}/{teamMember}', 'App\\Http\\Controllers\\Web\\Organization\\TeamAccessController@markArrived')
    ->name('rescue.arrived')
    ->middleware('signed');

require __DIR__ . '/auth.php';
