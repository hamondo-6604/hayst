<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the driver dashboard.
     */
    public function index(Request $request)
    {
        return view('driver.dashboard', [
            'driver' => $request->user(),
        ]);
    }
}
