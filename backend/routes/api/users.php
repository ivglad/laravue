<?php

use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('users', [UserController::class, 'index'])->name('user.index');
Route::get('users/{user}', [UserController::class, 'show'])->name('user.show');
Route::post('users', [UserController::class, 'store'])->name('user.store');
Route::put('users/{user}', [UserController::class, 'update'])->name('user.update');
Route::patch('users/{user}/send-verification', [UserController::class, 'sendVerification'])->name('user.send-verification');
Route::delete('users', [UserController::class, 'destroy'])->name('user.destroy');
Route::patch('users/password', [UserController::class, 'changePassword'])->name('user.change-password');

Route::get('roles', [RoleController::class, 'index'])->name('role.index');
Route::get('roles/{role}', [RoleController::class, 'show'])->name('role.show');
Route::post('roles', [RoleController::class, 'store'])->name('role.store');
Route::put('roles/{role}', [RoleController::class, 'update'])->name('role.update');
Route::delete('roles', [RoleController::class, 'destroy'])->name('role.destroy');
