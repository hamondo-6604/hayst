<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageBookingController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $status = $request->get('status', 'all');
        $sort   = $request->get('sort', 'newest');

        $query = Booking::with([
            'trip.route.originCity',
            'trip.route.destinationCity',
            'trip.bus.type',
            'seat',
            'bookingSeats',
            'payment',
            'promotion',
        ])->where('user_id', $user->id);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        match ($sort) {
            'oldest'    => $query->oldest(),
            'departure' => $query->orderBy(
                Trip::select('departure_time')
                    ->whereColumn('trips.id', 'bookings.trip_id')
                    ->limit(1)
            ),
            default     => $query->latest(),
        };

        $bookings = $query->paginate(8)->withQueryString();

        // Tab counts
        $counts = Booking::where('user_id', $user->id)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status='confirmed' THEN 1 ELSE 0 END) as confirmed,
                SUM(CASE WHEN status='pending'   THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status='cancelled' THEN 1 ELSE 0 END) as cancelled
            ")
            ->first();

        // Profile stats
        $profileStats = [
            'totalBookings' => $counts->total,
            'totalSpent'    => Booking::where('user_id', $user->id)
                                ->whereIn('status', ['confirmed','completed'])
                                ->sum('amount_paid'),
            'tripsCompleted'=> $counts->completed,
        ];

        // Next upcoming trip
        $nextTrip = Booking::with(['trip.route.originCity', 'trip.route.destinationCity'])
            ->where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->whereHas('trip', fn ($q) => $q->where('departure_time', '>', now()))
            ->orderBy(
                Trip::select('departure_time')
                    ->whereColumn('trips.id', 'bookings.trip_id')
                    ->limit(1)
            )
            ->first();

        // Unread notifications
        $unreadCount = Notification::where('user_id', $user->id)
            ->where('is_read', false)->count();

        return view('pages.manage_booking', compact(
            'bookings', 'counts', 'profileStats',
            'nextTrip', 'unreadCount', 'status', 'sort'
        ));
    }

    public function cancel(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        if (! in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        $booking->update([
            'status'              => 'cancelled',
            'cancelled_at'        => now(),
            'cancellation_reason' => $request->input('reason', 'Customer request'),
        ]);

        $booking->seat?->update(['status' => 'available']);
        $booking->bookingSeats()->update(['status' => 'cancelled']);
        $booking->trip?->increment('available_seats', $booking->seat_count);

        return back()->with('success', 'Booking '.$booking->booking_reference.' has been cancelled.');
    }

    public function markNotificationsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }
}