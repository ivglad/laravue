<?php

use App\Http\Controllers\File\FileController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route::post('request-password', [AuthController::class, 'requestPassword'])->name('password.request');
// Восстановление через письмо
Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('password.forgot');
Route::post('reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

Route::middleware('auth:sanctum')->group(function() {
    Route::apiResource('users', UserController::class);
    Route::patch('users/{user}/password', [UserController::class, 'changePassword']);

    Route::delete('files', [FileController::class, 'destroy']);
    Route::post('files', [FileController::class, 'store']);
    Route::post('files/generate', [FileController::class, 'generate']);
});
