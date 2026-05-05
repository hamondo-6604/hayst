<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Display upcoming and active trips.
     */
    public function index(Request $request)
    {
        return view('driver.trips.index', [
            'driver' => $request->user(),
        ]);
    }

    /**
     * Display past/completed trips.
     */
    public function history(Request $request)
    {
        return view('driver.trips.history', [
            'driver' => $request->user(),
        ]);
    }
}
