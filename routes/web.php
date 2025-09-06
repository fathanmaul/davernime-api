<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/user', function () {
    return auth()->user();
})->middleware('auth:sanctum');

require __DIR__.'/auth.php';
