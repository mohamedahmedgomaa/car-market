<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Car Market API is running 🚗',
        'status' => 'ok'
    ]);
});
