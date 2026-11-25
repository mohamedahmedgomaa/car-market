<?php

use App\Http\Modules\Cars\Controllers\CarController;
use Illuminate\Support\Facades\Route;

Route::get('cars/compare', [CarController::class, 'compare']);
Route::apiResource('cars', CarController::class);
Route::post('cars/bulk-status', [CarController::class, 'bulkUpdateStatus']);
Route::patch('cars/{car}/status', [CarController::class, 'updateStatus']);
Route::patch('cars/{car}/seller-status', [CarController::class, 'updateSellerStatus']);

Route::middleware('auth:api')->group(function () {
    Route::post('/cars/{car}/favorite', [CarController::class, 'toggleFavoriteCar']);
});
