<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Driver\DashboardController;
use App\Http\Controllers\Driver\TripController;
use App\Http\Controllers\Driver\MaintenanceController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Trips
Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
Route::get('/trips/history', [TripController::class, 'history'])->name('trips.history');
Route::post('/trips/{trip}/location', [TripController::class, 'updateLocation'])->name('trips.location.update');

// Maintenance
Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
