<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EmissionPointController;
use App\Http\Controllers\API\MeasurementController;
use App\Http\Controllers\API\StatisticController;

// 1. Les Routes Publics (Ma-khttajch Token)
Route::post('/login', [AuthController::class, 'login']);

// 2. Les Routes Protégés (Khss Vue.js y-passi l-Bearer Token f l-Headers)
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth & Session
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Bento Dashboard Statistics (Pour ApexCharts & Cards)
    Route::get('/dashboard-stats', [StatisticController::class, 'getDashboardStats']);

    // GIS / Carte interactive & les points d'émission
    Route::get('/emission-points', [EmissionPointController::class, 'index']);
    Route::post('/emission-points', [EmissionPointController::class, 'store']); 

    // Gestion des flux de mesures et Import CSV
    Route::post('/measurements', [MeasurementController::class, 'store']);
    Route::post('/measurements/import-csv', [MeasurementController::class, 'importCSV']);
    
});