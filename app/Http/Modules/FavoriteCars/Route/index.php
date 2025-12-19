<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('favorite_cars', \App\Http\Modules\FavoriteCars\Controllers\FavoriteCarController::class);
