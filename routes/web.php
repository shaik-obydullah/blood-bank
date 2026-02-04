<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardLoginController;
use App\Http\Controllers\BloodGroupController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\BloodDistributionController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BloodInventoryController;
use App\Http\Controllers\DonorAuthController;
use App\Http\Controllers\DonorDashboardController;

use App\Http\Controllers\DonorController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/search-blood', [HomeController::class, 'searchBlood']);
Route::post('/blood-request', [HomeController::class, 'save_request']);

Route::get('/donor-registration', [HomeController::class, 'donor_registration']);
Route::post('/save-donor', [HomeController::class, 'save_donor_registration']);

Route::middleware('donor')->group(function () {
    Route::get('/donor-dashboard', [DonorDashboardController::class, 'index']);
    Route::get('/donor-logout', [DonorDashboardController::class, 'logout']);
    Route::get('/donor-appointment', [DonorDashboardController::class, 'appointments']);
    Route::post('/appointment-store', [DonorDashboardController::class, 'storeAppointment']);
    Route::get('/donor-history', [DonorDashboardController::class, 'history']);
    Route::get('/donor-profile', [DonorDashboardController::class, 'profile']);
});

Route::get('/donor-login', [DonorAuthController::class, 'showLoginForm']);
Route::post('/check-donor-login', [DonorAuthController::class, 'login']);

Route::get('/admin-login', [DashboardLoginController::class, 'index']);
Route::post('/check-admin-login', [DashboardLoginController::class, 'login']);

Route::get('/admin-dashboard', [AdminDashboardController::class, 'index'])->middleware('admin');
Route::get('/admin-logout', [AdminDashboardController::class, 'logout'])->middleware('admin');

Route::prefix('blood-groups')->middleware('admin')->group(function () {
    Route::get('/', [BloodGroupController::class, 'index'])->name('blood-groups.index');
    Route::get('/create', [BloodGroupController::class, 'create'])->name('blood-groups.create');
    Route::post('/', [BloodGroupController::class, 'store'])->name('blood-groups.store');
    Route::get('/{bloodGroup}', [BloodGroupController::class, 'show'])->name('blood-groups.show');
    Route::get('/{bloodGroup}/edit', [BloodGroupController::class, 'edit'])->name('blood-groups.edit');
    Route::put('/{bloodGroup}', [BloodGroupController::class, 'update'])->name('blood-groups.update');
    Route::delete('/{bloodGroup}', [BloodGroupController::class, 'destroy'])->name('blood-groups.destroy');
});

Route::prefix('donors')->middleware('admin')->group(function () {
    Route::get('/', [DonorController::class, 'index'])->name('donors.index');
    Route::get('/create', [DonorController::class, 'create'])->name('donors.create');
    Route::post('/', [DonorController::class, 'store'])->name('donors.store');
    Route::get('/{donor}', [DonorController::class, 'show'])->name('donors.show');
    Route::get('/{donor}/edit', [DonorController::class, 'edit'])->name('donors.edit');
    Route::put('/{donor}', [DonorController::class, 'update'])->name('donors.update');
    Route::delete('/{donor}', [DonorController::class, 'destroy'])->name('donors.destroy');
    Route::get('/search', [DonorController::class, 'search'])->name('donors.search');
});

Route::prefix('doctors')->middleware('admin')->group(function () {
    Route::get('/', [DoctorController::class, 'index'])->name('doctors.index');
    Route::get('/create', [DoctorController::class, 'create'])->name('doctors.create');
    Route::post('/', [DoctorController::class, 'store'])->name('doctors.store');
    Route::get('/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
    Route::get('/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctors.edit');
    Route::put('/{doctor}', [DoctorController::class, 'update'])->name('doctors.update');
    Route::delete('/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');
});

Route::prefix('appointments')->middleware('admin')->group(function () {
    Route::get('/', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/create', [AppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::get('/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
    Route::put('/{appointment}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
    Route::post('/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
});

Route::prefix('patients')->middleware('admin')->group(function () {
    Route::get('/', [PatientController::class, 'index'])->name('patients.index');
    Route::get('/create', [PatientController::class, 'create'])->name('patients.create');
    Route::post('/', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::get('/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
    Route::put('/{patient}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
});

Route::prefix('blood-inventory')->middleware('admin')->group(function () {
    Route::get('/', [BloodInventoryController::class, 'index'])->name('blood-inventory.index');
    Route::get('/create', [BloodInventoryController::class, 'create'])->name('blood-inventory.create');
    Route::post('/', [BloodInventoryController::class, 'store'])->name('blood-inventory.store');
    Route::get('/{bloodInventory}', [BloodInventoryController::class, 'show'])->name('blood-inventory.show');
    Route::get('/{bloodInventory}/edit', [BloodInventoryController::class, 'edit'])->name('blood-inventory.edit');
    Route::put('/{bloodInventory}', [BloodInventoryController::class, 'update'])->name('blood-inventory.update');
    Route::delete('/{bloodInventory}', [BloodInventoryController::class, 'destroy'])->name('blood-inventory.destroy');
});

Route::prefix('blood-distributions')->middleware('admin')->group(function () {
    Route::get('/', [BloodDistributionController::class, 'index'])->name('blood-distributions.index');
    Route::get('/create', [BloodDistributionController::class, 'create'])->name('blood-distributions.create');
    Route::post('/', [BloodDistributionController::class, 'store'])->name('blood-distributions.store');
    Route::get('/{bloodDistribution}', [BloodDistributionController::class, 'show'])->name('blood-distributions.show');
    Route::get('/{bloodDistribution}/edit', [BloodDistributionController::class, 'edit'])->name('blood-distributions.edit');
    Route::put('/{bloodDistribution}', [BloodDistributionController::class, 'update'])->name('blood-distributions.update');
    Route::delete('/{bloodDistribution}', [BloodDistributionController::class, 'destroy'])->name('blood-distributions.destroy');
    Route::post('/{bloodDistribution}/approve', [BloodDistributionController::class, 'approve'])->name('blood-distributions.approve');
    Route::post('/{bloodDistribution}/reject', [BloodDistributionController::class, 'reject'])->name('blood-distributions.reject');
});