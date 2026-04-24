<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\SeatType;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeatFactory extends Factory
{
    public function definition(): array
    {
        $bus      = Bus::inRandomOrder()->first() ?? Bus::factory()->create();
        $seatType = SeatType::where('name', $bus->default_seat_type ?? 'economy')->first();

        return [
            'bus_id'        => $bus->id,
            'seat_number'   => fake()->numberBetween(1, 14) . fake()->randomUpperLetter(),
            'seat_type'     => $bus->default_seat_type ?? 'economy',
            'seat_type_id'  => $seatType?->id,
            'status'        => 'available',
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function available(): static
    {
        return $this->state(fn () => ['status' => 'available']);
    }

    public function booked(): static
    {
        return $this->state(fn () => ['status' => 'booked']);
    }

    public function blocked(): static
    {
        return $this->state(fn () => ['status' => 'blocked']);
    }

    public function economy(): static
    {
        return $this->state(fn () => [
            'seat_type'    => 'economy',
            'seat_type_id' => SeatType::where('name', 'economy')->first()?->id,
        ]);
    }

    public function business(): static
    {
        return $this->state(fn () => [
            'seat_type'    => 'business',
            'seat_type_id' => SeatType::where('name', 'business')->first()?->id,
        ]);
    }

    public function sleeper(): static
    {
        return $this->state(fn () => [
            'seat_type'    => 'sleeper',
            'seat_type_id' => SeatType::where('name', 'sleeper')->first()?->id,
        ]);
    }
}