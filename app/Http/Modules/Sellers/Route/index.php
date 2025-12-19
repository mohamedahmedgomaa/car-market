<?php

use Illuminate\Support\Facades\Route;


Route::apiResource('seller', \App\Http\Modules\Sellers\Controllers\SellerController::class);

