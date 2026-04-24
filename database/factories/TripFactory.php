<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Driver;
use App\Models\Schedule;
use App\Models\Terminal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TripFactory extends Factory
{
    public function definition(): array
    {
        $bus           = Bus::inRandomOrder()->first() ?? Bus::factory()->create();
        $route         = BusRoute::inRandomOrder()->first() ?? BusRoute::factory()->create();
        $departureTime = fake()->dateTimeBetween('+1 day', '+60 days');
        $arrivalTime   = (clone $departureTime)->modify(
            '+' . ($route->estimated_duration_minutes ?? fake()->numberBetween(120, 600)) . ' minutes'
        );

        $schedule       = Schedule::where('route_id', $route->id)->inRandomOrder()->first();
        $depTerminal    = Terminal::where('city_id', $route->origin_city_id)->first();
        $arrTerminal    = Terminal::where('city_id', $route->destination_city_id)->first();

        return [
            'route_id'               => $route->id,
            'schedule_id'            => $schedule?->id,
            'bus_id'                 => $bus->id,
            'driver_id'              => Driver::inRandomOrder()->first()?->id
                                        ?? Driver::factory(),
            'departure_terminal_id'  => $depTerminal?->id,
            'arrival_terminal_id'    => $arrTerminal?->id,
            'trip_code'              => 'TR-' . strtoupper(Str::random(6)),
            'trip_date'              => $departureTime->format('Y-m-d'),
            'departure_time'         => $departureTime->format('Y-m-d H:i:s'),
            'arrival_time'           => $arrivalTime->format('Y-m-d H:i:s'),
            'available_seats'        => $bus->total_seats,
            'fare'                   => fake()->randomFloat(2, 150, 1500),
            'status'                 => 'scheduled',
            'is_active'              => true,
            'notes'                  => null,
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function ongoing(): static
    {
        return $this->state(fn () => [
            'status'         => 'ongoing',
            'departure_time' => now()->subHours(2)->format('Y-m-d H:i:s'),
            'arrival_time'   => now()->addHours(3)->format('Y-m-d H:i:s'),
            'trip_date'      => now()->toDateString(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status'         => 'completed',
            'is_active'      => false,
            'departure_time' => fake()->dateTimeBetween('-60 days', '-1 day')->format('Y-m-d H:i:s'),
            'trip_date'      => fake()->dateTimeBetween('-60 days', '-1 day')->format('Y-m-d'),
            'available_seats'=> 0,
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status'    => 'cancelled',
            'is_active' => false,
        ]);
    }

    public function upcoming(): static
    {
        return $this->state(fn () => [
            'status'    => 'scheduled',
            'is_active' => true,
        ]);
    }
}