<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('users', \App\Http\Modules\Users\Controllers\UserController::class);

