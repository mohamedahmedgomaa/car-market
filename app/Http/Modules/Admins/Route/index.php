<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('admin', \App\Http\Modules\Admins\Controllers\AdminController::class);

