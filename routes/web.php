<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
// use App\Http\Controllers\FindworkController; // No longer needed - using local database
use App\Http\Controllers\SavedJobController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\JobAlertController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\JobSyncController;
use App\Http\Controllers\ApplicationForwardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/jobs', [PageController::class, 'jobs'])->name('jobs');
Route::get('/jobs/{id}', [PageController::class, 'jobDetail'])->name('jobs.detail');
Route::get('/signin', [PageController::class, 'signin'])->name('signin');
Route::get('/signup', [PageController::class, 'signup'])->name('signup');

// Auth actions
// Admin/Portal: sync now trigger
Route::post('/companies/{company}/sync-now', [JobSyncController::class, 'syncNow'])->name('companies.sync-now');
Route::post('/signup', [AuthController::class, 'register'])->name('signup.post');
Route::post('/signin', [AuthController::class, 'login'])->name('signin.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google OAuth
Route::get('/auth/google/redirect', [SocialAuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('/auth/linkedin/redirect', [SocialAuthController::class, 'redirectToLinkedIn'])->name('linkedin.redirect');
Route::get('/auth/linkedin/callback', [SocialAuthController::class, 'handleLinkedInCallback'])->name('linkedin.callback');

// External jobs API proxy (Findwork) - DISABLED - Now using local database
// Route::get('/external-jobs', [FindworkController::class, 'index'])->name('external-jobs.index');

// Saved jobs API (auth-only)
Route::middleware('auth')->group(function () {
    Route::get('/api/saved-jobs', [SavedJobController::class, 'index']);
    Route::post('/api/saved-jobs', [SavedJobController::class, 'store']);
    Route::delete('/api/saved-jobs/{id}', [SavedJobController::class, 'destroy']);
});

// Sync endpoints for HR app (protect via X-Recruitsy-Sync-Token)
Route::post('/api/central-jobs/upsert', [JobSyncController::class, 'upsert'])->middleware('recruitsy.sync');
Route::post('/api/central-jobs/deactivate', [JobSyncController::class, 'deactivate'])->middleware('recruitsy.sync');

// Candidate application forwarding
Route::post('/api/jobs/{id}/apply', [ApplicationForwardController::class, 'apply']);
Route::post('/api/applications/{id}/retry', [ApplicationForwardController::class, 'retry'])->middleware('auth');

// Dashboard Routes (auth-only)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/applications', [PageController::class, 'applications'])->name('applications');
    Route::get('/saved-jobs', [PageController::class, 'savedJobs'])->name('saved-jobs');
    Route::get('/profile', [PageController::class, 'profile'])->name('profile');
    
    // Profile Edit Routes
    Route::get('/profile/edit/personal', [PageController::class, 'editPersonal'])->name('profile.edit.personal');

    Route::get('/profile/edit/skills', [PageController::class, 'editSkills'])->name('profile.edit.skills');
    Route::get('/profile/edit/experience', [PageController::class, 'editExperience'])->name('profile.edit.experience');
    Route::get('/profile/edit/education', [PageController::class, 'editEducation'])->name('profile.edit.education');
    Route::get('/profile/edit/resume', [PageController::class, 'editResume'])->name('profile.edit.resume');

// Profile API routes
Route::prefix('api/profile')->middleware('auth')->group(function () {
    Route::put('/personal-info', [\App\Http\Controllers\ProfileController::class, 'updatePersonalInfo']);
    Route::put('/professional-info', [\App\Http\Controllers\ProfileController::class, 'updateProfessionalInfo']);
    Route::put('/skills', [\App\Http\Controllers\ProfileController::class, 'updateSkills']);
    Route::put('/experience', [\App\Http\Controllers\ProfileController::class, 'updateExperience']);
    Route::put('/education', [\App\Http\Controllers\ProfileController::class, 'updateEducation']);
    Route::post('/resume', [\App\Http\Controllers\ProfileController::class, 'uploadResume']);
    Route::delete('/resume/{id}', [\App\Http\Controllers\ProfileController::class, 'deleteResume']);
    Route::put('/resume/{id}/primary', [\App\Http\Controllers\ProfileController::class, 'setPrimaryResume']);
});
    Route::get('/job-alerts', [PageController::class, 'jobAlerts'])->name('job-alerts');
    Route::get('/settings', [PageController::class, 'settings'])->name('settings');
    Route::get('/dashboard-jobs', [PageController::class, 'dashboardJobs'])->name('dashboard-jobs');
    Route::get('/dashboard-jobs/{id}', [PageController::class, 'dashboardJobDetail'])->name('dashboard-jobs.detail');
    Route::get('/messages', [PageController::class, 'messages'])->name('messages');

    // Settings
    Route::post('/settings/account', [SettingsController::class, 'updateAccount'])->name('settings.account');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::get('/api/sessions', function() {
        return \App\Models\UserSession::where('user_id', auth()->id())
            ->orderByDesc('last_activity')->get();
    });

    // Job Alerts CRUD (AJAX)
    Route::get('/api/job-alerts', [JobAlertController::class, 'index']);
    Route::post('/api/job-alerts', [JobAlertController::class, 'store']);
    Route::put('/api/job-alerts/{id}', [JobAlertController::class, 'update']);
    Route::delete('/api/job-alerts/{id}', [JobAlertController::class, 'destroy']);

    // Chat endpoints
    Route::get('/api/chat/conversations', [\App\Http\Controllers\ChatController::class, 'listConversations']);
    Route::get('/api/chat/conversations/{id}', [\App\Http\Controllers\ChatController::class, 'getMessages']);
    Route::post('/api/chat/conversations/{id}/messages', [\App\Http\Controllers\ChatController::class, 'sendMessage']);
});

// Include admin routes
require __DIR__.'/admin.php';
