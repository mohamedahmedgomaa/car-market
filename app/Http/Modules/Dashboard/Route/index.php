<?php

use Illuminate\Support\Facades\Route;
use App\Http\Modules\Dashboard\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index']);
