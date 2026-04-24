<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Driver;
use App\Models\Schedule;
use App\Models\Terminal;
use App\Models\Trip;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        $routes   = BusRoute::with(['originCity', 'destinationCity'])->where('status', 'active')->get();
        $buses    = Bus::where('status', 'active')->get();
        $drivers  = Driver::where('status', 'available')->get();

        if ($routes->isEmpty() || $buses->isEmpty() || $drivers->isEmpty()) {
            $this->command->warn('TripSeeder: routes, buses, or drivers are empty. Skipping.');
            return;
        }

        // ── 10 upcoming scheduled trips ──────────────────────────────────
        for ($i = 0; $i < 10; $i++) {
            $route    = $routes->random();
            $bus      = $buses->random();
            $driver   = $drivers->random();
            $schedule = Schedule::where('route_id', $route->id)->inRandomOrder()->first();

            [$depTerminal, $arrTerminal] = $this->resolveTerminals($route);

            $departure = now()->addDays(rand(1, 30))
                              ->setTime(rand(5, 20), rand(0, 1) * 30, 0);
            $arrival   = (clone $departure)->modify(
                '+' . ($route->estimated_duration_minutes ?? rand(120, 480)) . ' minutes'
            );

            Trip::create([
                'route_id'               => $route->id,
                'schedule_id'            => $schedule?->id,
                'bus_id'                 => $bus->id,
                'driver_id'              => $driver->id,
                'departure_terminal_id'  => $depTerminal?->id,
                'arrival_terminal_id'    => $arrTerminal?->id,
                'trip_code'              => 'TR-' . strtoupper(Str::random(6)),
                'trip_date'              => $departure->format('Y-m-d'),
                'departure_time'         => $departure->format('Y-m-d H:i:s'),
                'arrival_time'           => $arrival->format('Y-m-d H:i:s'),
                'available_seats'        => $bus->total_seats,
                'fare'                   => $this->fareForDistance($route->distance_km),
                'status'                 => 'scheduled',
                'is_active'              => true,
            ]);
        }

        // ── 3 ongoing trips ───────────────────────────────────────────────
        for ($i = 0; $i < 3; $i++) {
            $route    = $routes->random();
            $bus      = $buses->random();
            $driver   = $drivers->random();
            $schedule = Schedule::where('route_id', $route->id)->inRandomOrder()->first();

            [$depTerminal, $arrTerminal] = $this->resolveTerminals($route);

            $departure = now()->subHours(rand(1, 4));
            $arrival   = now()->addHours(rand(1, 6));

            Trip::create([
                'route_id'               => $route->id,
                'schedule_id'            => $schedule?->id,
                'bus_id'                 => $bus->id,
                'driver_id'              => $driver->id,
                'departure_terminal_id'  => $depTerminal?->id,
                'arrival_terminal_id'    => $arrTerminal?->id,
                'trip_code'              => 'TR-' . strtoupper(Str::random(6)),
                'trip_date'              => $departure->format('Y-m-d'),
                'departure_time'         => $departure->format('Y-m-d H:i:s'),
                'arrival_time'           => $arrival->format('Y-m-d H:i:s'),
                'available_seats'        => rand(0, $bus->total_seats),
                'fare'                   => $this->fareForDistance($route->distance_km),
                'status'                 => 'ongoing',
                'is_active'              => true,
            ]);
        }

        // ── 10 past completed trips ───────────────────────────────────────
        for ($i = 0; $i < 10; $i++) {
            $route    = $routes->random();
            $bus      = $buses->random();
            $driver   = $drivers->random();
            $schedule = Schedule::where('route_id', $route->id)->inRandomOrder()->first();

            [$depTerminal, $arrTerminal] = $this->resolveTerminals($route);

            $departure = now()->subDays(rand(1, 60))
                              ->setTime(rand(5, 20), rand(0, 1) * 30, 0);
            $arrival   = (clone $departure)->modify(
                '+' . ($route->estimated_duration_minutes ?? rand(120, 480)) . ' minutes'
            );

            Trip::create([
                'route_id'               => $route->id,
                'schedule_id'            => $schedule?->id,
                'bus_id'                 => $bus->id,
                'driver_id'              => $driver->id,
                'departure_terminal_id'  => $depTerminal?->id,
                'arrival_terminal_id'    => $arrTerminal?->id,
                'trip_code'              => 'TR-' . strtoupper(Str::random(6)),
                'trip_date'              => $departure->format('Y-m-d'),
                'departure_time'         => $departure->format('Y-m-d H:i:s'),
                'arrival_time'           => $arrival->format('Y-m-d H:i:s'),
                'available_seats'        => 0,
                'fare'                   => $this->fareForDistance($route->distance_km),
                'status'                 => 'completed',
                'is_active'              => false,
            ]);
        }

        $this->command->info('TripSeeder: seeded ' . Trip::count() . ' trips.');
    }

    // ------------------------------------------------------------------
    // HELPERS
    // ------------------------------------------------------------------

    private function resolveTerminals(BusRoute $route): array
    {
        $dep = $route->origin_terminal_id
            ? Terminal::find($route->origin_terminal_id)
            : Terminal::where('city_id', $route->origin_city_id)->first();

        $arr = $route->destination_terminal_id
            ? Terminal::find($route->destination_terminal_id)
            : Terminal::where('city_id', $route->destination_city_id)->first();

        return [$dep, $arr];
    }

    private function fareForDistance(?int $distanceKm): float
    {
        return max(150.00, round(($distanceKm ?? 200) * 2.50, 2));
    }
}