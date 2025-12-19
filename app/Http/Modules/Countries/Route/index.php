<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('countries', \App\Http\Modules\Countries\Controllers\CountryController::class);
