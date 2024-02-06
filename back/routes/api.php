<?php

use Illuminate\Support\Facades\Route;

// GET /
Route::get('/', function () {
    return response()
        ->json('API Library Challenge');
});
