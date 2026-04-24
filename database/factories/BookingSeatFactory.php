<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Seat;
use App\Models\SeatType;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingSeatFactory extends Factory
{
    public function definition(): array
    {
        $booking    = Booking::inRandomOrder()->first() ?? Booking::factory()->create();
        $seat       = Seat::where('bus_id', $booking->trip?->bus_id)
                          ->inRandomOrder()
                          ->first();
        $seatType   = SeatType::inRandomOrder()->first();

        return [
            'booking_id'     => $booking->id,
            'seat_id'        => $seat?->id ?? Seat::factory(),
            'seat_type_id'   => $seatType?->id,
            'passenger_name' => fake()->name(),
            'passenger_type' => fake()->randomElement(['adult', 'child', 'infant']),
            'seat_number'    => $seat?->seat_number ?? fake()->bothify('#?'),
            'fare'           => fake()->randomFloat(2, 150, 1500),
            'status'         => 'confirmed',
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function reserved(): static
    {
        return $this->state(fn () => ['status' => 'reserved']);
    }

    public function confirmed(): static
    {
        return $this->state(fn () => ['status' => 'confirmed']);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => ['status' => 'cancelled']);
    }

    public function adult(): static
    {
        return $this->state(fn () => ['passenger_type' => 'adult']);
    }

    public function child(): static
    {
        return $this->state(fn () => [
            'passenger_type' => 'child',
            'fare'           => fake()->randomFloat(2, 75, 750), // ~50% of adult fare
        ]);
    }
}