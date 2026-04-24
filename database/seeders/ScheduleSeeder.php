<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Driver;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $routes  = BusRoute::with(['originCity', 'destinationCity'])
            ->where('status', 'active')
            ->get();

        $buses   = Bus::where('status', 'active')->get();
        $drivers = Driver::where('status', 'available')->get();

        if ($routes->isEmpty() || $buses->isEmpty()) {
            $this->command->warn('ScheduleSeeder: no routes or buses found. Skipping.');
            return;
        }

        // Predefined departure times for common routes
        $departureTimes = ['05:00', '06:00', '07:00', '08:00', '10:00', '12:00', '14:00', '18:00', '20:00', '22:00'];

        foreach ($routes as $i => $route) {
            $bus           = $buses[$i % $buses->count()];
            $driver        = $drivers->isNotEmpty() ? $drivers[$i % $drivers->count()] : null;
            $durationMins  = $route->estimated_duration_minutes ?? 360;

            // Give each route 2–3 daily schedules
            $times = array_slice($departureTimes, $i % 6, 2);

            foreach ($times as $j => $depTime) {
                [$depH, $depM] = explode(':', $depTime);
                $arrTime = sprintf(
                    '%02d:%02d',
                    ((int)$depH + (int)floor($durationMins / 60)) % 24,
                    ((int)$depM + ($durationMins % 60)) % 60
                );

                // Base fare: ₱2.50 per km, minimum ₱150
                $fare = max(150, round(($route->distance_km ?? 200) * 2.50, 2));

                $code = 'SCH-'
                    . strtoupper(substr($route->originCity?->name ?? 'O', 0, 3))
                    . '-'
                    . strtoupper(substr($route->destinationCity?->name ?? 'D', 0, 3))
                    . '-' . str_replace(':', '', $depTime);

                Schedule::updateOrCreate(
                    ['schedule_code' => $code],
                    [
                        'route_id'       => $route->id,
                        'bus_id'         => $bus->id,
                        'driver_id'      => $driver?->id,
                        'days_of_week'   => ['daily'],
                        'departure_time' => $depTime . ':00',
                        'arrival_time'   => $arrTime . ':00',
                        'fare'           => $fare,
                        'valid_from'     => now()->toDateString(),
                        'valid_until'    => null,
                        'status'         => 'active',
                    ]
                );
            }
        }

        $this->command->info('ScheduleSeeder: seeded ' . Schedule::count() . ' schedules.');
    }
}

