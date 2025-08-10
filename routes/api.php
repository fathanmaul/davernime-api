<?php

use App\Http\Controllers\AnimeController;
use App\Http\Controllers\Master\GenreController;
use App\Http\Controllers\Master\LicensorController;
use App\Http\Controllers\Master\ProducerController;
use App\Http\Controllers\Master\SourceController;
use App\Http\Controllers\Master\StudioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/master')->group(function () {
    Route::resource('/genres', GenreController::class);
    Route::resource('/licensors', LicensorController::class);
    Route::resource('/producers', ProducerController::class);
    Route::resource('/sources', SourceController::class);
    Route::resource('/studios', StudioController::class);
});

Route::resource('/animes', AnimeController::class);
Route::post('/animes/thumbnail', [AnimeController::class, 'storeTempThumbnail']);