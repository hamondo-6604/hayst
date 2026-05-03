<?php

namespace Database\Seeders;

use App\Models\BusRoute;
use App\Models\City;
use App\Models\Terminal;
use Illuminate\Database\Seeder;

class BusRouteSeeder extends Seeder
{
    public function run(): void
    {
        $routes = [
            // Mindanao
            ['origin' => 'Davao City',   'destination' => 'General Santos','distance' => 120, 'duration' => 150],
            ['origin' => 'Davao City',   'destination' => 'Cagayan de Oro','distance' => 310, 'duration' => 360],
            ['origin' => 'Cagayan de Oro','destination'=> 'Iligan',        'distance' => 35,  'duration' => 60],
            ['origin' => 'General Santos','destination' => 'Koronadal',    'distance' => 50,  'duration' => 75],
            ['origin' => 'Davao City',   'destination' => 'Tagum',         'distance' => 55,  'duration' => 80],
            ['origin' => 'Cagayan de Oro','destination'=> 'Butuan',        'distance' => 200, 'duration' => 240],
            ['origin' => 'Davao City',   'destination' => 'Zamboanga',     'distance' => 500, 'duration' => 600],
        ];

        foreach ($routes as $routeData) {
            $originCity = City::where('name', $routeData['origin'])->first();
            $destCity   = City::where('name', $routeData['destination'])->first();

            if (! $originCity || ! $destCity) {
                $this->command->warn(
                    "Skipping {$routeData['origin']} → {$routeData['destination']}: city not found."
                );
                continue;
            }

            // Look up matching terminals (may be null if TerminalSeeder hasn't run yet)
            $originTerminal = Terminal::where('city_id', $originCity->id)->first();
            $destTerminal   = Terminal::where('city_id', $destCity->id)->first();

            BusRoute::updateOrCreate(
                [
                    'origin_city_id'      => $originCity->id,
                    'destination_city_id' => $destCity->id,
                ],
                [
                    'route_name'                 => $originCity->name . ' → ' . $destCity->name,
                    'origin_terminal_id'         => $originTerminal?->id,
                    'destination_terminal_id'    => $destTerminal?->id,
                    'distance_km'                => $routeData['distance'],
                    'estimated_duration_minutes' => $routeData['duration'],
                    'status'                     => 'active',
                ]
            );
        }

        $this->command->info('BusRouteSeeder: seeded ' . BusRoute::count() . ' routes.');
    }
}