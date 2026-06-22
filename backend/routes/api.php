<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AlertController;
use App\Http\Controllers\API\EmissionPointController;
use App\Http\Controllers\API\MeasurementController;
use App\Http\Controllers\API\StatisticController;
use App\Http\Controllers\API\SubstanceController;


// --- 1. Public Routes ---
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);

    // ====================================================================
    // RÔLE 1: AUDITOR (Ou n'importe quel rôle connecté - Lecture seule globale)
    // ====================================================================
    Route::middleware('role:AUDITOR,OPERATOR,HSE_ADMIN')->group(function () {
        Route::get('/dashboard-stats', [StatisticController::class, 'getDashboardStats']);
        Route::get('/emission-points', [EmissionPointController::class, 'index']);
        Route::get('/substances', [SubstanceController::class, 'index']);
        Route::get('/measurements', [MeasurementController::class, 'index']);
        Route::get('/alerts', [AlertController::class, 'index']);
    });

    // ====================================================================
    // RÔLE 2: OPERATOR (Saisie de données et Importation CSV)
    // ====================================================================
    Route::middleware('role:OPERATOR,HSE_ADMIN')->group(function () {
        Route::post('/measurements', [MeasurementController::class, 'store']);
        Route::post('/measurements/import-csv', [MeasurementController::class, 'importCSV']);
    });

    // ====================================================================
    // RÔLE 3: HSE_ADMIN (Gestion de la configuration de l'usine - Full Access)
    // ====================================================================
    Route::middleware('role:HSE_ADMIN')->group(function () {
        Route::post('/emission-points', [EmissionPointController::class, 'store']);
        Route::delete('/emission-points/{id}', [EmissionPointController::class, 'destroy']);
        Route::patch('/alerts/{alert}/status', [AlertController::class, 'updateStatus']);
    });

});
