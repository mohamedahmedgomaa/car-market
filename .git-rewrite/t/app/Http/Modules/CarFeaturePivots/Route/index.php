<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('car_feature_pivots', \App\Http\Modules\CarFeaturePivots\Controllers\CarFeaturePivotController::class);
