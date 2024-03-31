<?php

use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('recipe', RecipeController::class);
Route::patch('recipe/{recipe}/like', [RecipeController::class, 'like'])->name('recipe.like');
Route::patch('recipe/{recipe}/rate', [RecipeController::class, 'rate'])->name('recipe.rate');
Route::patch('recipe/{recipe}/collaborator', [RecipeController::class, 'add-collaborators'])->name('recipe.add_collaborators');

Route::resource('subscribers', SubscriberController::class);

Route::resource('newsletter', NewsletterController::class);
Route::patch('newsletter/{newsletter}/publish', [NewsletterController::class, 'publish'])->name('newsletter.publish');
