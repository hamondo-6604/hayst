<?php

use Illuminate\Support\Facades\Route;

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