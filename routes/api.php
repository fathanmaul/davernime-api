<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    Log::info('User retrieved', ['user' => $request->user()]);
    return $request->user();
});

Route::middleware(['auth:sanctum'])->get('/test', function(Request $request) {
});