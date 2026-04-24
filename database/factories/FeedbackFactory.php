<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    public function definition(): array
    {
        $booking = Booking::completed()->inRandomOrder()->first();

        return [
            'user_id'    => $booking?->user_id
                            ?? User::customer()->inRandomOrder()->first()?->id
                            ?? User::factory()->customer(),
            'booking_id' => $booking?->id,
            'trip_id'    => $booking?->trip_id
                            ?? Trip::inRandomOrder()->first()?->id,
            'rating'     => fake()->numberBetween(1, 5),
            'subject'    => fake()->randomElement([
                'Great trip!', 'On time departure', 'Comfortable ride',
                'Driver was rude', 'Bus was dirty', 'AC not working',
                'Excellent service', 'Delayed by 2 hours',
            ]),
            'comment'    => fake()->paragraph(),
            'type'       => fake()->randomElement(['trip', 'driver', 'bus', 'general']),
            'status'     => fake()->randomElement(['pending', 'reviewed', 'resolved']),
            'admin_reply'=> null,
            'replied_at' => null,
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function positive(): static
    {
        return $this->state(fn () => [
            'rating'  => fake()->numberBetween(4, 5),
            'subject' => fake()->randomElement(['Great trip!', 'Excellent service', 'Very comfortable']),
        ]);
    }

    public function negative(): static
    {
        return $this->state(fn () => [
            'rating'  => fake()->numberBetween(1, 2),
            'subject' => fake()->randomElement(['Bad experience', 'AC not working', 'Very late departure']),
        ]);
    }

    public function withReply(): static
    {
        return $this->state(fn () => [
            'status'      => 'resolved',
            'admin_reply' => 'Thank you for your feedback. We will look into this.',
            'replied_at'  => now(),
        ]);
    }
}