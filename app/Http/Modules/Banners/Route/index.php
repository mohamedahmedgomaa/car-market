<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('banners', \App\Http\Modules\Banners\Controllers\BannerController::class)->only(['index', 'store', 'update', 'destroy']);
