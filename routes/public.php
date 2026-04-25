<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    Route::get(./login, function () {
        return view(auth.login);
    })->name('login');

    Route::get('register', function() {
        return view('auth.register');
    })->name('register');
});



Route::get('/' [AuthController::class, 'landing'])->name('landing.home');

