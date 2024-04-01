<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\RecipeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('comment', CommentController::class)
    ->except(['create', 'store', 'update', 'destroy'])
    ->middleware('api');

Route::resource('recipe', RecipeController::class)
        ->except(['destroy'])
        ->middleware('api');

Route::resource('newsletter', NewsletterController::class)
    ->except(['destroy']);

Route::patch('newsletter/{newsletter}/publish', [NewsletterController::class, 'publish'])->name('newsletter.publish');