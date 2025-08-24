<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::name('auth.')
//     ->middleware(['api', 'throttle:global'])
//     ->prefix('auth')
//     ->group(function () {
//         Route::post('login', [AuthController::class, 'doLogin'])->name('login');
//         Route::post('register', [AuthController::class, 'doRegister'])->name('register')->middleware('guest');
//         Route::post('refresh', [AuthController::class, 'refreshToken'])
//             ->name('refresh')
//             ->middleware(['auth:sanctum', 'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value]);
//         Route::post('logout', [AuthController::class, 'doLogout'])
//             ->name('logout')
//             ->middleware('auth:sanctum');
//     });

// Route::name('user.')->middleware(['api'])
//     ->prefix('user')
//     ->group(function() {
//         Route::get('me', [AuthController::class, 'getUser'])->name('me');
//     });

// Route::prefix('master')
//     ->middleware(['api', 'auth:sanctum'])
//     ->group(function () {
//         Route::resources([
//             'genres'     => GenreController::class,
//             'licensors'  => LicensorController::class,
//             'producers'  => ProducerController::class,
//             'sources'    => SourceController::class,
//             'studios'    => StudioController::class,
//         ]);
//     });

// Route::resource('/animes', AnimeController::class)->middleware(['api']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// require __DIR__.'/auth.php';
