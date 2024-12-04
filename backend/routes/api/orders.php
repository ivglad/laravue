<?php

use App\Http\Controllers\Order\EstimateController;
use App\Http\Controllers\Order\EstimatePositionController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\PaymentController;
use Illuminate\Support\Facades\Route;

Route::post('orders', [OrderController::class, 'store']);
Route::put('orders/{order}', [OrderController::class, 'update']);
Route::post('orders/{order}/copy', [OrderController::class, 'copy']);
Route::get('orders', [OrderController::class, 'index']);
Route::get('orders/{order}', [OrderController::class, 'show']);
Route::delete('orders', [OrderController::class, 'destroy']);

Route::patch('orders/{order}/status', [OrderController::class, 'changeStatus']);


Route::post('estimates', [EstimateController::class, 'massStore']);
Route::put('estimates/{estimate}', [EstimateController::class, 'update']);
Route::delete('estimates/{estimate}', [EstimateController::class, 'destroy']);

Route::post('estimate-positions', [EstimatePositionController::class, 'store']);
Route::put('estimate-positions/{estimatePosition}', [EstimatePositionController::class, 'update']);
Route::delete('estimate-positions/{estimatePosition}', [EstimatePositionController::class, 'destroy']);


Route::post('payments', [PaymentController::class, 'store']);

