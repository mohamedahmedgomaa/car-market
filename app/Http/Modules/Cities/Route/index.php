<?php

use Illuminate\Support\Facades\Route;

Route::post('cities/bulk', [\App\Http\Modules\Cities\Controllers\CityController::class, 'bulkStore']);
Route::apiResource('cities', \App\Http\Modules\Cities\Controllers\CityController::class);

