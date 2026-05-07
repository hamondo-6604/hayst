<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserType;
use App\Models\DiscountType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        // Pull a random user type (fallback to null if seeders haven't run yet)
        $userType = UserType::inRandomOrder()->first();

        return [
            'name'             => fake()->name(),
            'email'            => fake()->unique()->safeEmail(),
            'phone'            => fake()->numerify('09#########'),
            'email_verified_at'=> now(),
            'password'         => Hash::make('password'),
            'remember_token'   => Str::random(10),
            'role'             => $userType?->name ?? 'customer',
            'user_type_id'     => $userType?->id,
            'discount_type_id' => null,   // no discount by default
            'status'           => 'active',
            'image_url'        => null,
        ];
    }

    // ─── States ───────────────────────────────────────────────────────────────

    public function admin(): static
    {
        return $this->state(function () {
            $type = UserType::where('name', 'admin')->first();
            return [
                'role'         => 'admin',
                'user_type_id' => $type?->id,
            ];
        });
    }

    public function driver(): static
    {
        return $this->state(function () {
            $type = UserType::where('name', 'driver')->first();
            return [
                'role'         => 'driver',
                'user_type_id' => $type?->id,
            ];
        });
    }

    public function customer(): static
    {
        return $this->state(function () {
            $type = UserType::where('name', 'customer')->first();
            return [
                'role'         => 'customer',
                'user_type_id' => $type?->id,
            ];
        });
    }

    public function seniorCitizen(): static
    {
        return $this->customer()->state(function () {
            $discount = DiscountType::where('name', 'senior_citizen')->first();
            return ['discount_type_id' => $discount?->id];
        });
    }

    public function pwd(): static
    {
        return $this->customer()->state(function () {
            $discount = DiscountType::where('name', 'pwd')->first();
            return ['discount_type_id' => $discount?->id];
        });
    }

    public function student(): static
    {
        return $this->customer()->state(function () {
            $discount = DiscountType::where('name', 'student')->first();
            return ['discount_type_id' => $discount?->id];
        });
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }

    public function blocked(): static
    {
        return $this->state(fn () => ['status' => 'blocked']);
    }
}