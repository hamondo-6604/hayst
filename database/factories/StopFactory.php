<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Terminal;
use Illuminate\Database\Eloquent\Factories\Factory;

class StopFactory extends Factory
{
    public function definition(): array
    {
        $city = City::inRandomOrder()->first() ?? City::factory()->create();

        return [
            'name'        => fake()->randomElement(['Stop', 'Station', 'Pickup Point', 'Hub'])
                             . ' – ' . $city->name . ' ' . fake()->streetName(),
            'code'        => strtoupper(fake()->unique()->bothify('???-####')),
            'city_id'     => $city->id,
            'terminal_id' => null,
            'address'     => fake()->streetAddress(),
            'latitude'    => fake()->latitude(5.0, 20.0),
            'longitude'   => fake()->longitude(116.0, 127.0),
            'type'        => fake()->randomElement(['pickup', 'dropoff', 'waypoint']),
            'status'      => 'active',
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function terminal(): static
    {
        return $this->state(function () {
            $terminal = Terminal::inRandomOrder()->first() ?? Terminal::factory()->create();
            return [
                'type'        => 'terminal',
                'terminal_id' => $terminal->id,
                'city_id'     => $terminal->city_id,
            ];
        });
    }

    public function pickup(): static
    {
        return $this->state(fn () => ['type' => 'pickup']);
    }

    public function dropoff(): static
    {
        return $this->state(fn () => ['type' => 'dropoff']);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => 'inactive']);
    }
}