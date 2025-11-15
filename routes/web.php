<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::prefix('api')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login'])
        ->middleware('throttle:login')
        ->name('api.auth.login');

    Route::post('auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::get('auth/profile', [AuthController::class, 'profile'])->name('api.auth.profile');
});

Route::view('/{any}', 'welcome')->where('any', '^(?!api).*$');
