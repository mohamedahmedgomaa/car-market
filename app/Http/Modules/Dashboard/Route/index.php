<?php

use Illuminate\Support\Facades\Route;
use App\Http\Modules\Dashboard\Controllers\DashboardController;

Route::get('dashboard', [DashboardController::class, 'index']);
