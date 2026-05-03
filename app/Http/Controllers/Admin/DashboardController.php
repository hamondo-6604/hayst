<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Trip;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'total_users'     => User::where('role', 'customer')->count(),
            'today_bookings'  => Booking::whereDate('created_at', $today)->count(),
            'active_trips'    => Trip::where('status', 'scheduled')->whereDate('trip_date', '>=', $today)->count(),
            'today_revenue'   => Payment::whereIn('status', ['paid', 'completed'])->whereDate('created_at', $today)->sum('amount'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}