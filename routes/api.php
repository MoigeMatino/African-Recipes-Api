<?php

use App\Http\Controllers\Api\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('comment', CommentController::class)
    ->except(['create', 'store', 'update', 'destroy'])
    ->middleware('api');
