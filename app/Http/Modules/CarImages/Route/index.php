<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('car_images', \App\Http\Modules\CarImages\Controllers\CarImageController::class);
