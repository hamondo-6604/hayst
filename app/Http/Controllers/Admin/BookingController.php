<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        // Clear new booking notifications when admin visits the bookings page
        if (auth()->check()) {
            auth()->user()->appNotifications()
                ->unread()
                ->where('type', 'booking_confirmed')
                ->update(['is_read' => true, 'read_at' => now()]);
        }

        $query = Booking::with(['user', 'trip.route.originCity', 'trip.route.destinationCity'])
            ->latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load([
            'user', 
            'trip.route.originCity', 
            'trip.route.destinationCity', 
            'trip.bus', 
            'bookingSeats',
            'payment'
        ]);

        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        $booking->status = $request->status;
        
        if ($request->status === 'cancelled') {
            $booking->cancelled_at = now();
            $booking->cancellation_reason = 'Cancelled by Administrator';
        }
        
        $booking->save();

        return redirect()->back()->with('success', "Booking status updated to {$request->status}.");
    }
}
