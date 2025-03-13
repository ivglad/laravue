<?php

use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('verify', [UserController::class, 'verify'])->name('auth.verify');
Route::post('forgot-password', [UserController::class, 'forgotPassword'])->name('password.forgot');
Route::post('reset-password', [UserController::class, 'resetPassword'])->name('password.reset');

Route::middleware('auth:sanctum')->group(function() {
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
});


