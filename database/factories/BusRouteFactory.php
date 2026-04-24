<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Terminal;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusRouteFactory extends Factory
{
    public function definition(): array
    {
        $cities = City::inRandomOrder()->limit(2)->get();

        $origin      = $cities->first()  ?? City::factory()->create();
        $destination = $cities->last()   ?? City::factory()->create();

        if ($origin->id === $destination->id) {
            $destination = City::where('id', '!=', $origin->id)->inRandomOrder()->first()
                           ?? City::factory()->create();
        }

        $distanceKm      = fake()->numberBetween(50, 900);
        $durationMinutes = (int) ($distanceKm * fake()->randomFloat(1, 0.8, 1.5));

        // Wire terminals if they exist for these cities
        $originTerminal = Terminal::where('city_id', $origin->id)->first();
        $destTerminal   = Terminal::where('city_id', $destination->id)->first();

        return [
            'route_name'                 => $origin->name . ' → ' . $destination->name,
            'origin_city_id'             => $origin->id,
            'destination_city_id'        => $destination->id,
            'origin_terminal_id'         => $originTerminal?->id,
            'destination_terminal_id'    => $destTerminal?->id,
            'distance_km'                => $distanceKm,
            'estimated_duration_minutes' => $durationMinutes,
            'status'                     => 'active',
            'description'                => 'Route from ' . $origin->name . ' to ' . $destination->name,
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => 'inactive']);
    }
}