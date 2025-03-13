<?php

use App\Http\Controllers\Notification\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('notifications', [NotificationController::class, 'index'])->name('notification.index');
Route::patch('notifications', [NotificationController::class, 'read'])->name('notification.read');
