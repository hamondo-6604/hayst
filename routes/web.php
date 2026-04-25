<?php

use Illuminate\Support\Facades\Route;

require __DIR__ . '/public.php';

Route::middleware(['web','admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(__DIR__ . './admin.php');

Route::middleware(['web','user'])
    ->prefix('user')
    ->as('user.')
    ->group(__DIR__ . './user.php')