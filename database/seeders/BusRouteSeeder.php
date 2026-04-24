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
            // Metro Manila → Luzon North
            ['origin' => 'Quezon City',  'destination' => 'Baguio',        'distance' => 250, 'duration' => 360],
            ['origin' => 'Manila',       'destination' => 'Laoag',         'distance' => 483, 'duration' => 540],
            ['origin' => 'Manila',       'destination' => 'Vigan',         'distance' => 408, 'duration' => 480],
            ['origin' => 'Manila',       'destination' => 'San Fernando',  'distance' => 80,  'duration' => 120],
            ['origin' => 'Quezon City',  'destination' => 'Laoag',         'distance' => 490, 'duration' => 555],
            ['origin' => 'Baguio',       'destination' => 'Laoag',         'distance' => 233, 'duration' => 300],

            // Metro Manila → Luzon South
            ['origin' => 'Manila',       'destination' => 'Batangas City', 'distance' => 110, 'duration' => 150],
            ['origin' => 'Manila',       'destination' => 'Legazpi',       'distance' => 560, 'duration' => 600],
            ['origin' => 'Manila',       'destination' => 'Naga',          'distance' => 450, 'duration' => 480],

            // Visayas
            ['origin' => 'Cebu City',    'destination' => 'Tagbilaran',    'distance' => 100, 'duration' => 180],
            ['origin' => 'Iloilo City',  'destination' => 'Bacolod',       'distance' => 80,  'duration' => 120],
            ['origin' => 'Cebu City',    'destination' => 'Tacloban',      'distance' => 310, 'duration' => 360],

            // Mindanao
            ['origin' => 'Davao City',   'destination' => 'General Santos','distance' => 120, 'duration' => 150],
            ['origin' => 'Davao City',   'destination' => 'Cagayan de Oro','distance' => 310, 'duration' => 360],
            ['origin' => 'Cagayan de Oro','destination'=> 'Iligan',        'distance' => 35,  'duration' => 60],
            ['origin' => 'General Santos','destination' => 'Koronadal',   'distance' => 50,  'duration' => 75],
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