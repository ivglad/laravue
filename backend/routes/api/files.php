<?php

use App\Http\Controllers\File\FileController;
use Illuminate\Support\Facades\Route;

Route::delete('files', [FileController::class, 'destroy'])->name('file.destroy');
Route::post('files', [FileController::class, 'store'])->name('file.store');
Route::patch('files', [FileController::class, 'reorder'])->name('file.reorder');
Route::get('files/{media}', [FileController::class, 'download'])->name('file.download');
