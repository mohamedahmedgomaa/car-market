<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('car_features', \App\Http\Modules\CarFeatures\Controllers\CarFeatureController::class);
