<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
// Route::post('auth/register', [UserController::class, 'register']);
// Route::post('auth/login', [UserController::class, 'login']);
// Route::post('auth/forgot-password', [UserController::class, 'forgotPassword']);
// Route::post('auth/reset-password', [UserController::class, 'resetPassword']);
Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

// Jobs public endpoints
Route::get('jobs', [\App\Http\Controllers\Api\JobController::class, 'index']);
Route::get('jobs/{id}', [\App\Http\Controllers\Api\JobController::class, 'show']);
Route::get('jobs/{id}/related', [\App\Http\Controllers\Api\JobController::class, 'relatedJobs']);
Route::get('job-categories', [\App\Http\Controllers\Api\JobController::class, 'categories']);

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
    Route::get('profile', [\App\Http\Controllers\Api\UserProfileController::class, 'show']);
    Route::put('profile', [\App\Http\Controllers\Api\UserProfileController::class, 'update']);
    Route::post('profile/upload-cv', [\App\Http\Controllers\Api\UserProfileController::class, 'uploadCV']);

    // User experience
    Route::apiResource('experiences', \App\Http\Controllers\Api\UserExperienceController::class);

    // User education
    Route::apiResource('education', \App\Http\Controllers\Api\UserEducationController::class);

    // User portfolio
    Route::apiResource('portfolio', \App\Http\Controllers\Api\UserPortfolioController::class);

    // Job applications
    Route::apiResource('applications', \App\Http\Controllers\Api\JobApplicationController::class);
});
