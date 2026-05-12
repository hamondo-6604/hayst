<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/email/verify', function () {
    return redirect('/')->with('message', 'Please verify your email address. Check your inbox for the verification link.');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/')->with('message', 'Email verified successfully!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Load Public Routes (includes Auth AJAX)
require base_path('routes/public.php');

// Admin Routes
Route::middleware(['web', 'auth', 'admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(base_path('routes/admin.php'));

// User/Customer Routes
Route::middleware(['web', 'auth', 'user'])
    ->prefix('user')
    ->as('user.')
    ->group(base_path('routes/user.php'));

// Driver Routes
Route::middleware(['web', 'auth', 'driver'])
    ->prefix('driver')
    ->as('driver.')
    ->group(base_path('routes/driver.php'));