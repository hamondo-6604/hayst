<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Driver;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ScheduleFactory extends Factory
{
    public function definition(): array
    {
        $route       = BusRoute::inRandomOrder()->first() ?? BusRoute::factory()->create();
        $depHour     = fake()->numberBetween(4, 22);
        $depMinute   = fake()->randomElement([0, 30]);
        $durationMins= $route->estimated_duration_minutes ?? 360;
        $arrHour     = ($depHour + (int) floor($durationMins / 60)) % 24;
        $arrMinute   = ($depMinute + ($durationMins % 60)) % 60;

        return [
            'route_id'       => $route->id,
            'bus_id'         => Bus::inRandomOrder()->first()?->id ?? Bus::factory(),
            'driver_id'      => Driver::inRandomOrder()->first()?->id ?? Driver::factory(),
            'schedule_code'  => 'SCH-' . strtoupper(Str::random(8)),
            'days_of_week'   => ['daily'],
            'departure_time' => sprintf('%02d:%02d:00', $depHour, $depMinute),
            'arrival_time'   => sprintf('%02d:%02d:00', $arrHour, $arrMinute),
            'fare'           => max(150, round(($route->distance_km ?? 200) * 2.50, 2)),
            'valid_from'     => now()->toDateString(),
            'valid_until'    => null,
            'status'         => 'active',
            'notes'          => null,
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function daily(): static
    {
        return $this->state(fn () => ['days_of_week' => ['daily']]);
    }

    public function weekdays(): static
    {
        return $this->state(fn () => [
            'days_of_week' => ['mon', 'tue', 'wed', 'thu', 'fri'],
        ]);
    }

    public function weekends(): static
    {
        return $this->state(fn () => [
            'days_of_week' => ['sat', 'sun'],
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => 'inactive']);
    }

    public function suspended(): static
    {
        return $this->state(fn () => ['status' => 'suspended']);
    }

    public function expiring(): static
    {
        return $this->state(fn () => [
            'valid_until' => now()->addDays(7)->toDateString(),
        ]);
    }
}