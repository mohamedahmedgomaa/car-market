<?php

use App\Http\Modules\Notifications\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::patch('notifications/read-all', [NotificationController::class, 'markAllAsRead']);
Route::patch('notifications/{id}/read', [NotificationController::class, 'markAsRead']);
Route::apiResource('notifications', NotificationController::class);
