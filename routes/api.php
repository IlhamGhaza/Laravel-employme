<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\JobController;
use App\Http\Controllers\API\JobApplicationController;
use App\Http\Controllers\API\UserProfileController;
use App\Http\Controllers\API\UserExperienceController;
use App\Http\Controllers\API\UserEducationController;
use App\Http\Controllers\API\UserPortfolioController;
use App\Http\Controllers\API\Auth\UserController;
// Public routes
// Route::post('auth/register', [UserController::class, 'register']);
// Route::post('auth/login', [UserController::class, 'login']);
// Route::post('auth/forgot-password', [UserController::class, 'forgotPassword']);
// Route::post('auth/reset-password', [UserController::class, 'resetPassword']);
Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

// Jobs public endpoints
Route::get('jobs', [JobController::class, 'index']);
Route::get('jobs/{id}', [JobController::class, 'show']);
Route::get('jobs/{id}/related', [JobController::class, 'relatedJobs']);
Route::get('job-categories', [JobController::class, 'categories']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User authentication
    // Route::get('auth/me', [UserController::class, 'me']);
    // Route::post('auth/logout', [UserController::class, 'logout']);
    // Route::post('auth/change-password', [UserController::class, 'changePassword']);
    // Route::post('auth/deactivate', [UserController::class, 'deactivate']);
    //logout
    Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

    // User profile
    Route::get('profile', [UserProfileController::class, 'show']);
    Route::put('profile', [UserProfileController::class, 'update']);
    Route::post('profile/upload-cv', [UserProfileController::class, 'uploadCV']);

    // User experience
    Route::apiResource('experiences', UserExperienceController::class);

    // User education
    Route::apiResource('education', UserEducationController::class);

    // User portfolio
    Route::apiResource('portfolio', UserPortfolioController::class);

    // Job applications
    Route::apiResource('applications', JobApplicationController::class);
});
