<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::name('auth.')->prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('guest')->name('login');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('guest')->name('register');

    Route::post('/logout', [LoginController::class, 'destroy'])
        ->middleware('auth:sanctum')->name('logout');
});