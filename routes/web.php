<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookUserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::apiResource('books', BookController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('book-users', BookUserController::class);
