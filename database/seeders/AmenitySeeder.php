<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Bus;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run(): void
    {
        $amenities = [
            // Comfort
            ['name' => 'reclining_seat',  'display_name' => 'Reclining Seat',   'icon' => 'fas fa-couch',          'category' => 'comfort',       'description' => 'Seats that recline up to 150°.'],
            ['name' => 'legroom',         'display_name' => 'Extra Legroom',     'icon' => 'fas fa-arrows-alt-v',   'category' => 'comfort',       'description' => 'Additional space between rows.'],
            ['name' => 'blanket_pillow',  'display_name' => 'Blanket & Pillow',  'icon' => 'fas fa-bed',            'category' => 'comfort',       'description' => 'Provided on overnight trips.'],
            ['name' => 'lie_flat',        'display_name' => 'Lie-Flat Seat',     'icon' => 'fas fa-bed',            'category' => 'comfort',       'description' => 'Fully reclining sleeper seat.'],
            ['name' => 'ac',              'display_name' => 'Air Conditioning',  'icon' => 'fas fa-snowflake',      'category' => 'comfort',       'description' => 'Fully air-conditioned interior.'],

            // Connectivity
            ['name' => 'wifi',            'display_name' => 'Free WiFi',         'icon' => 'fas fa-wifi',           'category' => 'connectivity',  'description' => 'Complimentary onboard WiFi.'],
            ['name' => 'usb_charging',    'display_name' => 'USB Charging',      'icon' => 'fas fa-plug',           'category' => 'connectivity',  'description' => 'USB-A port at every seat.'],
            ['name' => 'power_outlet',    'display_name' => 'Power Outlet',      'icon' => 'fas fa-bolt',           'category' => 'connectivity',  'description' => '220V outlet available.'],

            // Safety
            ['name' => 'seatbelt',        'display_name' => 'Seatbelt',          'icon' => 'fas fa-shield-alt',     'category' => 'safety',        'description' => '3-point seatbelt at every seat.'],
            ['name' => 'cctv',            'display_name' => 'CCTV Cameras',      'icon' => 'fas fa-video',          'category' => 'safety',        'description' => 'Interior and exterior CCTV.'],
            ['name' => 'gps_tracking',    'display_name' => 'GPS Tracking',      'icon' => 'fas fa-map-marker-alt', 'category' => 'safety',        'description' => 'Real-time trip GPS monitoring.'],
            ['name' => 'first_aid',       'display_name' => 'First Aid Kit',     'icon' => 'fas fa-first-aid',      'category' => 'safety',        'description' => 'Standard first aid kit onboard.'],

            // Service
            ['name' => 'meal_included',   'display_name' => 'Meal Included',     'icon' => 'fas fa-utensils',       'category' => 'service',       'description' => 'Light meal or snack included.'],
            ['name' => 'bottled_water',   'display_name' => 'Bottled Water',     'icon' => 'fas fa-tint',           'category' => 'service',       'description' => 'Complimentary bottled water.'],
            ['name' => 'restroom',        'display_name' => 'Onboard Restroom',  'icon' => 'fas fa-toilet',         'category' => 'service',       'description' => 'Clean restroom at the rear.'],

            // Entertainment
            ['name' => 'tv_screen',       'display_name' => 'TV / Monitor',      'icon' => 'fas fa-tv',             'category' => 'entertainment', 'description' => 'Entertainment screens installed.'],
            ['name' => 'reading_light',   'display_name' => 'Reading Light',     'icon' => 'fas fa-lightbulb',      'category' => 'entertainment', 'description' => 'Individual adjustable reading light.'],
        ];

        foreach ($amenities as $amenity) {
            Amenity::updateOrCreate(
                ['name' => $amenity['name']],
                array_merge($amenity, ['is_active' => true])
            );
        }

        // Assign sensible default amenities to existing buses
        $this->assignDefaultAmenities();
    }

    private function assignDefaultAmenities(): void
    {
        $economy  = ['ac', 'seatbelt', 'cctv', 'gps_tracking'];
        $business = ['ac', 'seatbelt', 'cctv', 'gps_tracking', 'reclining_seat', 'usb_charging', 'wifi'];
        $sleeper  = ['ac', 'seatbelt', 'cctv', 'gps_tracking', 'lie_flat', 'blanket_pillow', 'usb_charging', 'wifi', 'restroom'];

        $amenityMap = Amenity::whereIn('name', array_merge($economy, $business, $sleeper))
            ->pluck('id', 'name');

        Bus::all()->each(function (Bus $bus) use ($economy, $business, $sleeper, $amenityMap) {
            $type  = strtolower($bus->default_seat_type ?? 'economy');
            $names = match (true) {
                str_contains($type, 'sleep') => $sleeper,
                str_contains($type, 'busi')  => $business,
                default                      => $economy,
            };

            $ids = collect($names)
                ->filter(fn ($n) => isset($amenityMap[$n]))
                ->mapWithKeys(fn ($n) => [$amenityMap[$n] => ['note' => null]])
                ->toArray();

            $bus->amenities()->syncWithoutDetaching($ids);
        });
    }
}