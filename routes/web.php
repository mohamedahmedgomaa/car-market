<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'NegmCars API is running 🚗',
        'status' => 'ok'
    ]);
});
