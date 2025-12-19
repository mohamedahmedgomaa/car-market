<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('cars', \App\Http\Modules\Cars\Controllers\CarController::class);
Route::patch('cars/{car}/status', [\App\Http\Modules\Cars\Controllers\CarController::class, 'updateStatus']);
