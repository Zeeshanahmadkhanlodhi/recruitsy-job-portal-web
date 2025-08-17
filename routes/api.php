<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CentralJobIngestController;
use App\Http\Controllers\Api\ApplicationForwardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// HR -> Job Portal: Secure job ingest endpoints
Route::post('/central-jobs/upsert', [CentralJobIngestController::class, 'upsert'])->middleware('recruitsy.sync');
Route::post('/central-jobs/delete', [CentralJobIngestController::class, 'destroy'])->middleware('recruitsy.sync');

// Candidate applies to a portal-indexed job; forward to tenant
Route::post('/central-jobs/{job}/apply', [ApplicationForwardController::class, 'store']);

// Profile management routes
Route::middleware('auth:web')->group(function () {
    Route::put('/profile/personal-info', [App\Http\Controllers\ProfileController::class, 'updatePersonalInfo']);
    Route::put('/profile/professional-info', [App\Http\Controllers\ProfileController::class, 'updateProfessionalInfo']);
    Route::put('/profile/skills', [App\Http\Controllers\ProfileController::class, 'updateSkills']);
    Route::put('/profile/experience', [App\Http\Controllers\ProfileController::class, 'updateExperience']);
    Route::put('/profile/education', [App\Http\Controllers\ProfileController::class, 'updateEducation']);
    Route::post('/profile/resume', [App\Http\Controllers\ProfileController::class, 'uploadResume']);
    Route::delete('/profile/resume/{id}', [App\Http\Controllers\ProfileController::class, 'deleteResume']);
    Route::put('/profile/resume/{id}/primary', [App\Http\Controllers\ProfileController::class, 'setPrimaryResume']);
});
