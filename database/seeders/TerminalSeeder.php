<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Terminal;
use Illuminate\Database\Seeder;

class TerminalSeeder extends Seeder
{
    public function run(): void
    {
        $terminals = [
            // Metro Manila
            ['city' => 'Quezon City',   'name' => 'Cubao Bus Terminal',           'code' => 'CBT',  'address' => 'EDSA cor. New York Ave., Cubao, Quezon City',         'lat' => 14.6196,  'lng' => 121.0531],
            ['city' => 'Pasay',         'name' => 'Pasay Bus Terminal (PITX)',     'code' => 'PITX', 'address' => 'PITX, Coastal Rd., Pasay City',                       'lat' => 14.5337,  'lng' => 120.9902],
            ['city' => 'Manila',        'name' => 'Sampaloc Bus Terminal',         'code' => 'SBT',  'address' => 'Lacson Ave., Sampaloc, Manila',                       'lat' => 14.6131,  'lng' => 120.9942],
            ['city' => 'Pasig',         'name' => 'Pasig Bus Terminal',            'code' => 'PBT',  'address' => 'Ortigas Ave., Pasig City',                            'lat' => 14.5865,  'lng' => 121.0735],

            // Luzon
            ['city' => 'Baguio',        'name' => 'Baguio City Bus Terminal',      'code' => 'BAG',  'address' => 'Slaughter House Rd., Baguio City',                   'lat' => 16.4023,  'lng' => 120.5960],
            ['city' => 'Laoag',         'name' => 'Laoag Bus Terminal',            'code' => 'LAO',  'address' => 'Rizal St., Laoag City, Ilocos Norte',                'lat' => 18.1981,  'lng' => 120.5935],
            ['city' => 'Vigan',         'name' => 'Vigan Bus Terminal',            'code' => 'VIG',  'address' => 'Quezon Ave., Vigan City, Ilocos Sur',                 'lat' => 17.5747,  'lng' => 120.3869],
            ['city' => 'Legazpi',       'name' => 'Legazpi Bus Terminal',          'code' => 'LGZ',  'address' => 'Penaranda St., Legazpi City, Albay',                 'lat' => 13.1391,  'lng' => 123.7438],
            ['city' => 'Naga',          'name' => 'Naga Bus Terminal',             'code' => 'NGA',  'address' => 'Magsaysay Ave., Naga City, Camarines Sur',            'lat' => 13.6218,  'lng' => 123.1945],

            // Visayas
            ['city' => 'Cebu City',     'name' => 'Cebu South Bus Terminal',       'code' => 'CSBT', 'address' => 'N. Bacalso Ave., Cebu City',                         'lat' => 10.2908,  'lng' => 123.8822],
            ['city' => 'Cebu City',     'name' => 'Cebu North Bus Terminal',       'code' => 'CNBT', 'address' => 'Jakosalem St., Cebu City',                            'lat' => 10.3182,  'lng' => 123.9003],
            ['city' => 'Iloilo City',   'name' => 'Iloilo Bus Terminal (QCPO)',    'code' => 'ILO',  'address' => 'Quezon St., Iloilo City',                             'lat' => 10.7202,  'lng' => 122.5621],
            ['city' => 'Bacolod',       'name' => 'Bacolod Bus Terminal',          'code' => 'BAC',  'address' => 'Lacson St., Bacolod City',                            'lat' => 10.6765,  'lng' => 122.9509],
            ['city' => 'Tacloban',      'name' => 'Tacloban Bus Terminal',         'code' => 'TAC',  'address' => 'Magsaysay Blvd., Tacloban City',                     'lat' => 11.2448,  'lng' => 125.0040],

            // Mindanao
            ['city' => 'Davao City',    'name' => 'Davao Ecoland Bus Terminal',    'code' => 'DVO',  'address' => 'Ecoland Drive, Davao City',                          'lat' => 7.0897,   'lng' => 125.6145],
            ['city' => 'Cagayan de Oro','name' => 'CDO Agora Bus Terminal',        'code' => 'CDO',  'address' => 'Mortola St., Cagayan de Oro City',                   'lat' => 8.4776,   'lng' => 124.6500],
            ['city' => 'General Santos','name' => 'GenSan Bus Terminal',           'code' => 'GEN',  'address' => 'Santiago Blvd., General Santos City',                'lat' => 6.1164,   'lng' => 125.1716],
            ['city' => 'Zamboanga',     'name' => 'Zamboanga Bus Terminal',        'code' => 'ZAM',  'address' => 'Veterans Ave., Zamboanga City',                      'lat' => 6.9101,   'lng' => 122.0730],
        ];

        foreach ($terminals as $data) {
            $city = City::where('name', $data['city'])->first();

            if (! $city) {
                $this->command->warn("TerminalSeeder: city '{$data['city']}' not found — skipping {$data['name']}.");
                continue;
            }

            Terminal::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name'           => $data['name'],
                    'city_id'        => $city->id,
                    'address'        => $data['address'],
                    'latitude'       => $data['lat'],
                    'longitude'      => $data['lng'],
                    'opening_time'   => '04:00:00',
                    'closing_time'   => '23:59:00',
                    'status'         => 'active',
                ]
            );
        }

        // Wire terminals back to routes (origin + destination)
        $this->linkTerminalsToRoutes();
    }

    private function linkTerminalsToRoutes(): void
    {
        \App\Models\BusRoute::with(['originCity', 'destinationCity'])->get()
            ->each(function ($route) {
                $origin = Terminal::whereHas('city', fn ($q) =>
                    $q->where('id', $route->origin_city_id)
                )->first();

                $dest = Terminal::whereHas('city', fn ($q) =>
                    $q->where('id', $route->destination_city_id)
                )->first();

                $route->update([
                    'origin_terminal_id'      => $origin?->id,
                    'destination_terminal_id' => $dest?->id,
                ]);
            });
    }
}