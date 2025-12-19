<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('cities', \App\Http\Modules\Cities\Controllers\CityController::class);
