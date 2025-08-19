<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\JobManagementController;
use App\Http\Controllers\Admin\CompanyManagementController;
use App\Http\Controllers\Admin\ApplicationManagementController;
use App\Http\Controllers\Admin\ImportController;

// Admin Authentication Routes
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    // Guest routes (no authentication required)
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
    });

    // Protected admin routes
    Route::middleware('admin.auth')->group(function () {
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Profile management
        Route::get('profile', [AuthController::class, 'showProfile'])->name('profile');
        Route::put('profile', [AuthController::class, 'updateProfile'])->name('profile.update');
        
        // Logout
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        // Import management
        Route::get('import', [ImportController::class, 'index'])->name('import.index');
        Route::post('import/companies', [ImportController::class, 'importCompanies'])->name('import.companies');
        Route::post('import/jobs', [ImportController::class, 'importJobs'])->name('import.jobs');
        Route::get('import/sample/{type}', [ImportController::class, 'downloadSample'])->name('import.sample');

        // User management
        Route::resource('users', UserManagementController::class);
        Route::post('users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // Job management
        Route::resource('jobs', JobManagementController::class);
        Route::post('jobs/{job}/toggle-status', [JobManagementController::class, 'toggleStatus'])->name('jobs.toggle-status');
        
        // Company management
        Route::resource('companies', CompanyManagementController::class);
        Route::post('companies/{company}/toggle-status', [CompanyManagementController::class, 'toggleStatus'])->name('companies.toggle-status');
        
        // Application management
        Route::resource('applications', ApplicationManagementController::class);
        Route::post('applications/{application}/retry', [ApplicationManagementController::class, 'retry'])->name('applications.retry');
        
        // Admin management
        Route::resource('admins', AdminManagementController::class);
        Route::post('admins/{admin}/toggle-status', [AdminManagementController::class, 'toggleStatus'])->name('admins.toggle-status');
    });
});
