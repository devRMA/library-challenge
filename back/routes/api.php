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
});
