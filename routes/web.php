<?php

use App\Http\Controllers\TriageRecordController;
use App\Http\Controllers\AllocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminAuditController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminWardController;
use App\Http\Controllers\BedManager\BedBoardController;
use App\Http\Controllers\Doctor\DoctorQueueController;
use App\Http\Controllers\Nurse\NursePatientController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('User/Pages/Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('User/Pages/Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('role:Admin')
        ->name('admin.dashboard');

    Route::get('/admin/users', [AdminUserController::class, 'index'])
        ->middleware('role:Admin')
        ->name('admin.users');

    Route::post('/admin/users', [AdminUserController::class, 'store'])
        ->middleware('role:Admin')
        ->name('admin.users.store');

    Route::patch('/admin/users/{user}', [AdminUserController::class, 'update'])
        ->middleware('role:Admin')
        ->name('admin.users.update');

    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])
        ->middleware('role:Admin')
        ->name('admin.users.destroy');

    Route::get('/admin/wards', [AdminWardController::class, 'index'])
        ->middleware('role:Admin')
        ->name('admin.wards');

    Route::post('/admin/wards', [AdminWardController::class, 'store'])
        ->middleware('role:Admin')
        ->name('admin.wards.store');

    Route::patch('/admin/wards/{ward}', [AdminWardController::class, 'update'])
        ->middleware('role:Admin')
        ->name('admin.wards.update');

    Route::delete('/admin/wards/{ward}', [AdminWardController::class, 'destroy'])
        ->middleware('role:Admin')
        ->name('admin.wards.destroy');

    Route::get('/admin/audit', [AdminAuditController::class, 'index'])
        ->middleware('role:Admin')
        ->name('admin.audit');

    Route::get('/nurse/triage/new', fn () => Inertia::render('Nurse/Triage/New'))
        ->middleware('role:Triage Nurse')
        ->name('nurse.triage.new');

    Route::get('/nurse/patients', [NursePatientController::class, 'index'])
        ->middleware('role:Triage Nurse')
        ->name('nurse.patients');

    Route::get('/bed-manager/beds', [BedBoardController::class, 'index'])
        ->middleware('role:Bed Manager')
        ->name('bed-manager.beds');

    Route::post('/bed-manager/beds', [BedBoardController::class, 'store'])
        ->middleware('role:Bed Manager')
        ->name('bed-manager.beds.store');

    Route::patch('/bed-manager/beds/{bed}', [BedBoardController::class, 'update'])
        ->middleware('role:Bed Manager')
        ->name('bed-manager.beds.update');

    Route::delete('/bed-manager/beds/{bed}', [BedBoardController::class, 'destroy'])
        ->middleware('role:Bed Manager')
        ->name('bed-manager.beds.destroy');

    Route::get('/doctor/queue', [DoctorQueueController::class, 'index'])
        ->middleware('role:Doctor')
        ->name('doctor.queue');

    Route::post('/triage-records', [TriageRecordController::class, 'store'])
        ->middleware('role:Triage Nurse')
        ->name('triage-records.store');

    Route::patch('/allocations/{allocation}', [AllocationController::class, 'update'])
        ->middleware('role:Bed Manager,Doctor')
        ->name('allocations.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
