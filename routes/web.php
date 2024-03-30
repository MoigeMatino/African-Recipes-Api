<?php

use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('subscribers', SubscriberController::class);
