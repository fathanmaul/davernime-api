<?php

use App\Http\Controllers\AnimeController;
use App\Http\Controllers\Master\GenreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    Log::info('User retrieved', context: ['user' => $request->user()]);
    return $request->user();
});

Route::prefix('master')->name('master.')->group(function () {
    Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
});

Route::prefix('anime')->name('anime.')->group(function () {
    Route::get('/', [AnimeController::class, 'index'])->name('index');
    Route::get('/{id}', [AnimeController::class, 'show'])->name('show');
});