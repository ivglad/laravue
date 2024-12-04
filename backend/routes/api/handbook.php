<?php

use App\Http\Controllers\Handbook\CounterpartyController;
use App\Http\Controllers\Handbook\HandbookController;
use App\Http\Controllers\Handbook\PositionController;
use Illuminate\Support\Facades\Route;


Route::get('', [HandbookController::class, 'index']);

Route::post('counterparties', [CounterpartyController::class, 'store']);
Route::put('counterparties/{counterparty}', [CounterpartyController::class, 'update']);
Route::get('counterparties', [CounterpartyController::class, 'index']);
Route::get('counterparties/{counterparty}', [CounterpartyController::class, 'show']);
Route::delete('counterparties', [CounterpartyController::class, 'destroy']);
Route::post('counterparties/{counterparty}/merger', [CounterpartyController::class, 'merger']);

Route::apiResource('positions', PositionController::class);
