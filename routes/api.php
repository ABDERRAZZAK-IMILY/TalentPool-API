<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnoncesControllers;
use App\Http\Controllers\CandidaturesController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RecruiterStatisticsController;


    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);


    Route::middleware('jwt.auth')->group(function () {
        // Annonces Routes
        Route::apiResource('annonces', AnnoncesControllers::class);
        
        // Candidatures Routes
        Route::apiResource('candidatures', CandidaturesController::class);
        
        // Admin Statistics Routes
        Route::prefix('admin')->group(function () {
            Route::get('statistics', [AdminController::class, 'getGlobalStatistics']);
            Route::get('statistics/recruiter/{recruiterId}', [AdminController::class, 'getRecruiterStatistics']);
            Route::get('statistics/annonce/{annonceId}', [AdminController::class, 'getAnnonceStatistics']);
            Route::get('users', [AdminController::class, 'getUserManagementData']);
        });
        
        // Recruiter Statistics Routes
        Route::prefix('recruiter/statistics')->group(function () {
            Route::get('/', [RecruiterStatisticsController::class, 'getMyStatistics']);
            Route::get('annonce/{annonceId}', [RecruiterStatisticsController::class, 'getAnnonceStatistics']);
            Route::get('application-status', [RecruiterStatisticsController::class, 'getApplicationStatusDistribution']);
            Route::get('recent-activity', [RecruiterStatisticsController::class, 'getRecentActivity']);
        });
    });