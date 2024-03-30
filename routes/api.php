<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('api')->group(function () {
    // Show all comments
    Route::get('/comments', [CommentController::class,'index'])->name('comments.index');

    // Add a new comment
    Route::post('/comments/create', [CommentController::class, 'store'])->name('comments.store');

    // Delete a specific comment by its ID
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Edit the content of a specific comment 
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');

    // Display the form to edit the content of a specific comment
    Route::get('/comments/edit',[CommentController::class, 'edit'])->name('comments.edit'); 

});



