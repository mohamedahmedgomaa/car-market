<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('models', \App\Http\Modules\Models\Controllers\ModelController::class);
