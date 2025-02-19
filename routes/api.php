<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompaniesController;
use App\Http\Controllers\Api\JobController;

//register
Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('companies', [CompaniesController::class, 'index']);
    Route::get('companies/show/{id}', [CompaniesController::class, 'show']);
    Route::post('companies', [CompaniesController::class, 'store']);
    Route::put('companies/{id}', [CompaniesController::class, 'update']);
    Route::delete('companies/{id}', [CompaniesController::class, 'destroy']);

    // Job routes
    Route::get('jobs', [JobController::class, 'index']);
    Route::get('jobs/show/{id}', [JobController::class, 'show']);
    Route::post('jobs', [JobController::class, 'store']);
    Route::put('jobs/{id}', [JobController::class, 'update']);
    Route::delete('jobs/{id}', [JobController::class, 'destroy']);
});
