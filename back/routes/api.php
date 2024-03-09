<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// GET /
Route::get('/', function () {
    return response()
        ->json('API Library Challenge');
});

Route::group([
    'middleware' => ['auth'],
    'where' => [
        'client' => '[0-9]+',
        'book' => '[0-9]+',
    ],
], function () {
    Route::group([
        'prefix' => 'user',
        'controller' => UserController::class,
    ], function () {
        // GET user/profile-information
        Route::get('profile-information', 'profileInformation')
            ->name('user-profile-information.index');

        // GET user/devices
        Route::get('devices', 'devices')
            ->name('user-devices.index');

        // DELETE user/devices
        Route::delete('devices', 'logoutOtherDevices')
            ->name('user-devices.delete');
    });

    Route::apiResource('clients', ClientController::class);
    Route::apiResource('books', BookController::class);

    Route::group([
        'as' => 'clients.',
        'prefix' => 'clients',
        'controller' => ClientController::class,
    ], function () {
        // GET clients/{client}/history
        Route::get('{client}/history', 'rentalHistory')
            ->name('history.index');

        // POST clients/{client}/rent/{book}
        Route::post('{client}/rent/{book}', 'rentBook')
            ->name('rent.start');

        // DELETE clients/{client}/rent/{book}
        Route::delete('{client}/rent/{book}', 'returnBook')
            ->name('rent.end');
    });

    Route::group([
        'as' => 'books.',
        'prefix' => 'books',
        'controller' => BookController::class,
    ], function () {
        // GET books/{book}/history
        Route::get('{book}/history', 'rentalHistory')
            ->name('history.index');
    });
});
