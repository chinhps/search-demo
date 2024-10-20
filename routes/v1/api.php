<?php

use App\Http\Controllers\v1\Measurement\MeasurementController;
use Illuminate\Support\Facades\Route;

# Measurement
Route::post("measurement", [MeasurementController::class, 'register']);
Route::get("measurement", [MeasurementController::class, 'test']);
