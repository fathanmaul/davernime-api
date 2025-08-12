<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Master\GenreController;
use App\Http\Controllers\Master\LicensorController;
use App\Http\Controllers\Master\ProducerController;
use App\Http\Controllers\Master\SourceController;
use App\Http\Controllers\Master\StudioController;
use Illuminate\Support\Facades\Route;

Route::name('auth.')->middleware(['api'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'doLogin'])->name('login');
        Route::post('register', [AuthController::class, 'doRegister'])->name('register');
        Route::post('refresh', [AuthController::class, 'refreshToken'])->name('refresh')->middleware(['auth:sanctum', 'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value]);
        Route::post('logout', [AuthController::class, 'doLogout'])->name('logout')->middleware('auth:sanctum');
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'getUser'])->name('me');
    });
});


Route::prefix('/master')->middleware(['api', 'auth:sanctum', 'ensureIsAdmin'])->group(function () {
    Route::resource('/genres', GenreController::class);
    Route::resource('/licensors', LicensorController::class);
    Route::resource('/producers', ProducerController::class);
    Route::resource('/sources', SourceController::class);
    Route::resource('/studios', StudioController::class);
});

Route::resource('/animes', AnimeController::class)->middleware(['api']);