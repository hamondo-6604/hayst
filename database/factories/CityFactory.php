<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    // Real Philippine cities for realistic seed data
    private static array $cities = [
        ['name' => 'Manila',       'province' => 'Metro Manila',  'region' => 'NCR'],
        ['name' => 'Cebu City',    'province' => 'Cebu',          'region' => 'Region VII'],
        ['name' => 'Davao City',   'province' => 'Davao del Sur', 'region' => 'Region XI'],
        ['name' => 'Quezon City',  'province' => 'Metro Manila',  'region' => 'NCR'],
        ['name' => 'Iloilo City',  'province' => 'Iloilo',        'region' => 'Region VI'],
        ['name' => 'Bacolod',      'province' => 'Negros Occ.',   'region' => 'Region VI'],
        ['name' => 'Cagayan de Oro','province' => 'Misamis Or.',  'region' => 'Region X'],
        ['name' => 'Zamboanga',    'province' => 'Zamboanga City','region' => 'Region IX'],
        ['name' => 'Baguio',       'province' => 'Benguet',       'region' => 'CAR'],
        ['name' => 'Laoag',        'province' => 'Ilocos Norte',  'region' => 'Region I'],
        ['name' => 'Legazpi',      'province' => 'Albay',         'region' => 'Region V'],
        ['name' => 'Tacloban',     'province' => 'Leyte',         'region' => 'Region VIII'],
    ];

    private static int $index = 0;

    public function definition(): array
    {
        $city = self::$cities[self::$index % count(self::$cities)];
        self::$index++;

        return [
            'name'     => $city['name'],
            'province' => $city['province'],
            'region'   => $city['region'],
            'status'   => 'active',
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => 'inactive']);
    }
}