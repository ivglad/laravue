<?php

use App\Http\Controllers\Activity\ActivityController;
use Illuminate\Support\Facades\Route;

Route::get('activities', [ActivityController::class, 'index'])->name('activity.index');
Route::get('activities/handbook', [ActivityController::class, 'handbook'])->name('activity.handbook');

