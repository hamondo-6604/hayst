<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * Display maintenance requests.
     */
    public function index(Request $request)
    {
        return view('driver.maintenance.index', [
            'driver' => $request->user(),
        ]);
    }
}
