<?php

use Illuminate\Support\Facades\Route;

require base_path('routes/public.php');

Route::middleware(['web','admin'])
    ->prefix('admin')
    ->as('admin.')
    ->group(base_path('routes/admin.php'));

Route::middleware(['web','user'])
    ->prefix('user')
    ->as('user.')
    ->group(base_path('routes/user.php'));