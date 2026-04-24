<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class TerminalFactory extends Factory
{
    public function definition(): array
    {
        $city = City::inRandomOrder()->first() ?? City::factory()->create();

        return [
            'name'           => $city->name . ' Bus Terminal',
            'code'           => strtoupper(fake()->unique()->bothify('???')),
            'city_id'        => $city->id,
            'address'        => fake()->streetAddress() . ', ' . $city->name,
            'latitude'       => fake()->latitude(5.0, 20.0),   // Philippines lat range
            'longitude'      => fake()->longitude(116.0, 127.0), // Philippines lng range
            'contact_number' => fake()->numerify('0##-###-####'),
            'email'          => fake()->companyEmail(),
            'opening_time'   => '04:00:00',
            'closing_time'   => '23:59:00',
            'status'         => 'active',
            'description'    => 'Main bus terminal in ' . $city->name . '.',
            'photo'          => null,
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => 'inactive']);
    }

    public function underConstruction(): static
    {
        return $this->state(fn () => ['status' => 'under_construction']);
    }

    public function forCity(City $city): static
    {
        return $this->state(fn () => [
            'city_id' => $city->id,
            'name'    => $city->name . ' Bus Terminal',
        ]);
    }
}