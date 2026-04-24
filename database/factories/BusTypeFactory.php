<?php

namespace Database\Factories;

use App\Models\SeatLayout;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type_name'      => fake()->randomElement([
                'Ordinary', 'Aircon', 'Deluxe', 'Premium', 'Sleeper',
            ]),
            'seat_layout_id' => SeatLayout::inRandomOrder()->first()?->id
                                ?? SeatLayout::factory(),
            'status'         => 'active',
            'description'    => fake()->sentence(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => 'inactive']);
    }
}