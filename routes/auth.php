<?php

use App\Http\Controllers\Auth\TelegramUserController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('login', [TelegramUserController::class, 'create'])
        ->name('login');

    Route::post('login', [TelegramUserController::class, 'store']);

});

Route::middleware('auth')->group(function () {
    Route::post('logout', [TelegramUserController::class, 'destroy'])
        ->name('logout');
});
