<?php

namespace Database\Factories;

use App\Models\BookingSeat;
use App\Models\Promotion;
use App\Models\Seat;
use App\Models\SeatType;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $trip     = Trip::inRandomOrder()->first() ?? Trip::factory()->create();
        $seat     = Seat::where('bus_id', $trip->bus_id)
                        ->where('status', 'available')
                        ->inRandomOrder()
                        ->first();
        $user     = User::customer()->inRandomOrder()->first() ?? User::factory()->customer()->create();
        $baseFare = (float) ($trip->fare ?? fake()->randomFloat(2, 150, 1500));

        // Apply user type discount if any
        $discount = $baseFare - $user->calculateFare($baseFare);

        return [
            'user_id'             => $user->id,
            'trip_id'             => $trip->id,
            'seat_id'             => $seat?->id,
            'promotion_id'        => null,
            'seat_number'         => $seat?->seat_number ?? fake()->bothify('#?'),
            'status'              => 'confirmed',
            'base_fare'           => $baseFare,
            'discount_amount'     => $discount,
            'amount_paid'         => max(0, $baseFare - $discount),
            'payment_status'      => 'paid',
            'booking_reference'   => 'BKG-' . strtoupper(Str::random(8)),
            'cancelled_at'        => null,
            'cancellation_reason' => null,
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function pending(): static
    {
        return $this->state(fn () => [
            'status'         => 'pending',
            'payment_status' => 'unpaid',
            'amount_paid'    => 0.00,
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn () => [
            'status'         => 'confirmed',
            'payment_status' => 'paid',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status'         => 'completed',
            'payment_status' => 'paid',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status'              => 'cancelled',
            'payment_status'      => 'unpaid',
            'amount_paid'         => 0.00,
            'cancelled_at'        => now(),
            'cancellation_reason' => fake()->randomElement([
                'Customer request', 'Trip cancelled',
                'Duplicate booking', 'Payment not received',
            ]),
        ]);
    }

    public function withPromotion(): static
    {
        return $this->state(function (array $attributes) {
            $promo    = Promotion::valid()->inRandomOrder()->first()
                        ?? Promotion::factory()->create();
            $discount = $promo->calculateDiscount($attributes['base_fare']);

            return [
                'promotion_id'    => $promo->id,
                'discount_amount' => $discount,
                'amount_paid'     => max(0, $attributes['base_fare'] - $discount),
            ];
        });
    }

    // ------------------------------------------------------------------
    // CONFIGURE — auto-create a BookingSeat after creating a Booking
    // ------------------------------------------------------------------

    public function configure(): static
    {
        return $this->afterCreating(function ($booking) {
            if (! $booking->seat_id) {
                return;
            }

            $seatTypeId = SeatType::where(
                'name',
                $booking->seat?->seat_type ?? $booking->bus?->default_seat_type ?? 'economy'
            )->first()?->id;

            BookingSeat::firstOrCreate(
                ['booking_id' => $booking->id, 'seat_id' => $booking->seat_id],
                [
                    'seat_type_id'   => $seatTypeId,
                    'passenger_name' => $booking->user?->name ?? 'Passenger',
                    'passenger_type' => 'adult',
                    'seat_number'    => $booking->seat_number,
                    'fare'           => $booking->amount_paid,
                    'status'         => $booking->status === 'cancelled' ? 'cancelled' : 'confirmed',
                ]
            );
        });
    }
}