<?php

use App\Http\Controllers\v1\Measurement\MeasurementController;
use Illuminate\Support\Facades\Route;

# Measurement
Route::prefix('measurement')->group(function () {
    Route::post('/', [MeasurementController::class, 'register']);
    Route::get('/', [MeasurementController::class, 'resultRetrieval']);
    Route::get('/{id}', [MeasurementController::class, 'resultDetail']);
});
