<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('governorates', \App\Http\Modules\Governorates\Controllers\GovernorateController::class);
