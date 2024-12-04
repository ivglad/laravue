<?php

use App\Http\Controllers\Comment\CommentController;
use Illuminate\Support\Facades\Route;

Route::post('comments', [CommentController::class, 'store']);
Route::put('comments/{comment}', [CommentController::class, 'update']);
Route::delete('comments', [CommentController::class, 'destroy']);

