<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('brands', \App\Http\Modules\Brands\Controllers\BrandController::class);
