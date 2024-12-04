<?php

use App\Http\Controllers\Report\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('reports', [ReportController::class, 'report'])->name('reports');

