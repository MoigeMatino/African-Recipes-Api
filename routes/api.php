<?php

use App\Http\Controllers\Api\ApiCommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resource('comment', ApiCommentController::class)
    ->except(['create', 'store', 'update', 'destroy'])
    ->middleware('api');

Route::resource('newsletter', NewsletterController::class)
    ->except(['destroy']);

Route::patch('newsletter/{newsletter}/publish', [NewsletterController::class, 'publish'])->name('newsletter.publish');