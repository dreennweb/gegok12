<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\DocumentController;

/* Applicant Portal Routes */
Route::middleware(['auth', 'verified'])->prefix('applicant')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'applicantDashboard'])->name('applicant.dashboard');
    Route::get('/registrations/create', function () {
        return view('registrations.create');
    })->name('registrations.create');
    Route::get('/applications', function () {
        return view('applications.index');
    })->name('applications.index');
    Route::get('/applications/{application}', function () {
        return view('applications.show');
    })->name('applications.show');
});

/* Department Portal Routes */
Route::middleware(['auth', 'verified', 'ensure.department.access'])->prefix('department')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'departmentStaffDashboard'])->name('department.dashboard');
    Route::get('/applications/{application}', function () {
        return view('department.applications.show');
    })->name('department.applications.show');
});

/* Superadmin Portal Routes */
Route::middleware(['auth', 'verified', 'ensure.superadmin.access'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'superadminDashboard'])->name('admin.dashboard');
});

/* API Routes */
Route::middleware('auth:sanctum')->prefix('api')->group(function () {
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::get('/applications/{application}', [ApplicationController::class, 'show']);
    Route::post('/applications/{application}/submit', [ApplicationController::class, 'submit']);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download']);
    Route::get('/documents/{document}/signed-url', [DocumentController::class, 'getSignedUrl']);
});
