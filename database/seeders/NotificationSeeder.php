<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Send a booking_confirmed notification for every confirmed booking
        $confirmedBookings = Booking::where('status', 'confirmed')
            ->with('trip.route')
            ->get();

        foreach ($confirmedBookings as $booking) {
            $routeName = $booking->trip?->route?->route_name ?? 'your trip';

            Notification::create([
                'user_id'          => $booking->user_id,
                'title'            => 'Booking Confirmed',
                'message'          => "Your booking for {$routeName} (Ref: {$booking->booking_reference}) has been confirmed.",
                'type'             => 'booking_confirmed',
                'notifiable_type'  => Booking::class,
                'notifiable_id'    => $booking->id,
                'is_read'          => (bool) rand(0, 1),
                'read_at'          => null,
            ]);
        }

        // Trip reminder for all upcoming confirmed bookings
        foreach ($confirmedBookings->take(10) as $booking) {
            Notification::create([
                'user_id'         => $booking->user_id,
                'title'           => 'Trip Reminder',
                'message'         => "Reminder: Your trip departs on {$booking->trip?->departure_time?->format('M d, Y h:i A')}. Please arrive 30 minutes early.",
                'type'            => 'trip_reminder',
                'notifiable_type' => Booking::class,
                'notifiable_id'   => $booking->id,
                'is_read'         => false,
                'read_at'         => null,
            ]);
        }

        // Promo notifications for all customers
        $customers = User::where('role', 'customer')->get();
        foreach ($customers as $customer) {
            Notification::create([
                'user_id' => $customer->id,
                'title'   => 'Special Promo!',
                'message' => 'Use code WELCOME20 to get 20% off your next booking. Limited slots only!',
                'type'    => 'promotion',
                'is_read' => (bool) rand(0, 1),
            ]);
        }
    }
}