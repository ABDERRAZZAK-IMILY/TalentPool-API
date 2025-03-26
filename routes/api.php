<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnoncesControllers;
use App\Http\Controllers\CandidaturesController;
use App\Http\Controllers\AnnonceController;


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
    });





