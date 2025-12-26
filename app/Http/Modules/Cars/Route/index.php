<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('cars', \App\Http\Modules\Cars\Controllers\CarController::class);
Route::patch('cars/{car}/status', [\App\Http\Modules\Cars\Controllers\CarController::class, 'updateStatus']);

Route::middleware('auth:api')->group(function () {
    Route::post('/cars/{car}/favorite', [\App\Http\Modules\Cars\Controllers\CarController::class, 'toggleFavoriteCar']);
});
