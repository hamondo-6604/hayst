<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserType;
use App\Models\DiscountType;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminType    = UserType::where('name', 'admin')->first();
        $driverType   = UserType::where('name', 'driver')->first();
        $customerType = UserType::where('name', 'customer')->first();

        $seniorDiscount  = DiscountType::where('name', 'senior_citizen')->first();
        $pwdDiscount     = DiscountType::where('name', 'pwd')->first();
        $studentDiscount = DiscountType::where('name', 'student')->first();

        // ── Admin ─────────────────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@busco.ph'],
            [
                'name'             => 'System Admin',
                'password'         => Hash::make('password'),
                'role'             => 'admin',
                'user_type_id'     => $adminType?->id,
                'discount_type_id' => null,   // admins don't book tickets
                'status'           => 'active',
                'phone'            => '09000000001',
            ]
        );

        // ── Driver ────────────────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'driver@busco.ph'],
            [
                'name'             => 'Juan dela Cruz',
                'password'         => Hash::make('password'),
                'role'             => 'driver',
                'user_type_id'     => $driverType?->id,
                'discount_type_id' => null,
                'status'           => 'active',
                'phone'            => '09000000002',
            ]
        );

        User::factory()->driver()->count(4)->create();

        // ── Customers ─────────────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'customer@busco.ph'],
            [
                'name'             => 'Maria Santos',
                'password'         => Hash::make('password'),
                'role'             => 'customer',
                'user_type_id'     => $customerType?->id,
                'discount_type_id' => null,   // regular, no discount
                'status'           => 'active',
                'phone'            => '09000000003',
            ]
        );

        User::updateOrCreate(
            ['email' => 'senior@busco.ph'],
            [
                'name'             => 'Lola Rosario',
                'password'         => Hash::make('password'),
                'role'             => 'customer',
                'user_type_id'     => $customerType?->id,
                'discount_type_id' => $seniorDiscount?->id,
                'status'           => 'active',
                'phone'            => '09000000004',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pwd@busco.ph'],
            [
                'name'             => 'Pedro Reyes',
                'password'         => Hash::make('password'),
                'role'             => 'customer',
                'user_type_id'     => $customerType?->id,
                'discount_type_id' => $pwdDiscount?->id,
                'status'           => 'active',
                'phone'            => '09000000005',
            ]
        );

        User::updateOrCreate(
            ['email' => 'student@busco.ph'],
            [
                'name'             => 'Carlo Reyes',
                'password'         => Hash::make('password'),
                'role'             => 'customer',
                'user_type_id'     => $customerType?->id,
                'discount_type_id' => $studentDiscount?->id,
                'status'           => 'active',
                'phone'            => '09000000006',
            ]
        );
    }
}