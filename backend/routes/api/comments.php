<?php

use App\Http\Controllers\Comment\CommentController;
use Illuminate\Support\Facades\Route;

Route::post('comments', [CommentController::class, 'store'])->name('comment.store');
Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comment.update');
Route::delete('comments', [CommentController::class, 'destroy'])->name('comment.destroy');
