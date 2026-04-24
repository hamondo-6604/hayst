<?php

namespace Database\Factories;

use App\Models\BusType;
use App\Models\SeatLayout;
use App\Models\SeatType;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusFactory extends Factory
{
    public function definition(): array
    {
        $layout       = SeatLayout::inRandomOrder()->first() ?? SeatLayout::factory()->create();
        $seatTypeName = fake()->randomElement(['economy', 'business']);
        $seatType     = SeatType::where('name', $seatTypeName)->first();

        return [
            'bus_number'            => strtoupper(fake()->unique()->bothify('??-####')),
            'bus_name'              => fake()->randomElement([
                'Batangas Star', 'Victory Liner', 'Philtranco',
                'Genesis Transport', 'Florida Bus', 'Five Star',
                'Partas Liner', 'Dominion Bus', 'Bachelor Express',
            ]) . ' ' . fake()->numberBetween(1, 99),
            'bus_type_id'           => BusType::inRandomOrder()->first()?->id
                                       ?? BusType::factory(),
            'seat_layout_id'        => $layout->id,
            'total_seats'           => $layout->effective_capacity,
            'default_seat_type'     => $seatTypeName,
            'default_seat_type_id'  => $seatType?->id,
            'bus_img'               => null,
            'status'                => 'active',
            'description'           => fake()->sentence(),
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function economy(): static
    {
        return $this->state(fn () => [
            'default_seat_type'    => 'economy',
            'default_seat_type_id' => SeatType::where('name', 'economy')->first()?->id,
        ]);
    }

    public function business(): static
    {
        return $this->state(fn () => [
            'default_seat_type'    => 'business',
            'default_seat_type_id' => SeatType::where('name', 'business')->first()?->id,
        ]);
    }

    public function sleeper(): static
    {
        return $this->state(fn () => [
            'default_seat_type'    => 'sleeper',
            'default_seat_type_id' => SeatType::where('name', 'sleeper')->first()?->id,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => 'inactive']);
    }

    public function maintenance(): static
    {
        return $this->state(fn () => ['status' => 'maintenance']);
    }
}