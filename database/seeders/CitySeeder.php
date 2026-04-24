<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            // NCR
            ['name' => 'Manila',        'province' => 'Metro Manila',       'region' => 'NCR'],
            ['name' => 'Quezon City',   'province' => 'Metro Manila',       'region' => 'NCR'],
            ['name' => 'Pasay',         'province' => 'Metro Manila',       'region' => 'NCR'],
            ['name' => 'Pasig',         'province' => 'Metro Manila',       'region' => 'NCR'],

            // Region I
            ['name' => 'Laoag',         'province' => 'Ilocos Norte',       'region' => 'Region I'],
            ['name' => 'Vigan',         'province' => 'Ilocos Sur',         'region' => 'Region I'],
            ['name' => 'San Fernando',  'province' => 'La Union',           'region' => 'Region I'],

            // CAR
            ['name' => 'Baguio',        'province' => 'Benguet',            'region' => 'CAR'],

            // Region III
            ['name' => 'San Fernando',  'province' => 'Pampanga',           'region' => 'Region III'],
            ['name' => 'Olongapo',      'province' => 'Zambales',           'region' => 'Region III'],

            // Region IV-A
            ['name' => 'Batangas City', 'province' => 'Batangas',           'region' => 'Region IV-A'],
            ['name' => 'Lucena',        'province' => 'Quezon',             'region' => 'Region IV-A'],

            // Region V
            ['name' => 'Legazpi',       'province' => 'Albay',              'region' => 'Region V'],
            ['name' => 'Naga',          'province' => 'Camarines Sur',      'region' => 'Region V'],

            // Region VI
            ['name' => 'Iloilo City',   'province' => 'Iloilo',             'region' => 'Region VI'],
            ['name' => 'Bacolod',       'province' => 'Negros Occidental',  'region' => 'Region VI'],

            // Region VII
            ['name' => 'Cebu City',     'province' => 'Cebu',               'region' => 'Region VII'],
            ['name' => 'Mandaue',       'province' => 'Cebu',               'region' => 'Region VII'],
            ['name' => 'Tagbilaran',    'province' => 'Bohol',              'region' => 'Region VII'],

            // Region VIII
            ['name' => 'Tacloban',      'province' => 'Leyte',              'region' => 'Region VIII'],

            // Region IX
            ['name' => 'Zamboanga',     'province' => 'Zamboanga City',     'region' => 'Region IX'],

            // Region X
            ['name' => 'Cagayan de Oro','province' => 'Misamis Oriental',   'region' => 'Region X'],
            ['name' => 'Iligan',        'province' => 'Lanao del Norte',    'region' => 'Region X'],

            // Region XI
            ['name' => 'Davao City',    'province' => 'Davao del Sur',      'region' => 'Region XI'],

            // Region XII
            ['name' => 'General Santos','province' => 'South Cotabato',     'region' => 'Region XII'],
            ['name' => 'Koronadal',     'province' => 'South Cotabato',     'region' => 'Region XII'],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(
                ['name' => $city['name'], 'province' => $city['province']],
                array_merge($city, ['status' => 'active'])
            );
        }
    }
}