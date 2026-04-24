<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement([
            'booking_confirmed', 'booking_cancelled', 'payment_received',
            'trip_reminder', 'trip_update', 'promotion', 'general',
        ]);

        [$title, $message] = $this->contentForType($type);

        return [
            'user_id'          => User::inRandomOrder()->first()?->id ?? User::factory(),
            'title'            => $title,
            'message'          => $message,
            'type'             => $type,
            'notifiable_type'  => null,
            'notifiable_id'    => null,
            'is_read'          => fake()->boolean(40),
            'read_at'          => null,
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function unread(): static
    {
        return $this->state(fn () => [
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    public function read(): static
    {
        return $this->state(fn () => [
            'is_read' => true,
            'read_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    // ------------------------------------------------------------------
    // HELPERS
    // ------------------------------------------------------------------

    private function contentForType(string $type): array
    {
        return match ($type) {
            'booking_confirmed'  => ['Booking Confirmed',    'Your booking has been confirmed. Have a safe trip!'],
            'booking_cancelled'  => ['Booking Cancelled',    'Your booking has been cancelled. A refund will be processed.'],
            'payment_received'   => ['Payment Received',     'We have received your payment. Your seat is secured.'],
            'trip_reminder'      => ['Trip Reminder',        'Your trip departs tomorrow. Please be at the terminal 30 minutes early.'],
            'trip_update'        => ['Trip Update',          'There has been a schedule update for your upcoming trip.'],
            'promotion'          => ['Special Promo!',       'Use code ' . strtoupper(fake()->bothify('????##')) . ' to get a discount on your next booking.'],
            default              => ['Notice',               fake()->sentence()],
        };
    }
}