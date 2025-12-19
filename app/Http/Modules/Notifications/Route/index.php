<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('notifications', \App\Http\Modules\Notifications\Controllers\NotificationController::class);
