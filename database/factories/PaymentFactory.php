<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $booking = Booking::inRandomOrder()->first() ?? Booking::factory()->create();

        return [
            'booking_id'       => $booking->id,
            'amount'           => $booking->amount_paid,
            'payment_method'   => fake()->randomElement([
                'gcash', 'maya', 'cash', 'card', 'bank_transfer',
            ]),
            'status'           => 'paid',
            'transaction_id'   => strtoupper(Str::random(16)),
            'gateway_response' => null,
            'currency'         => 'PHP',
            'paid_at'          => now(),
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function pending(): static
    {
        return $this->state(fn () => [
            'status'         => 'pending',
            'transaction_id' => null,
            'paid_at'        => null,
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn () => [
            'status'  => 'failed',
            'paid_at' => null,
            'gateway_response' => [
                'error'   => 'insufficient_funds',
                'message' => 'The card was declined.',
            ],
        ]);
    }

    public function refunded(): static
    {
        return $this->state(fn () => ['status' => 'refunded']);
    }

    public function gcash(): static
    {
        return $this->state(fn () => ['payment_method' => 'gcash']);
    }

    public function cash(): static
    {
        return $this->state(fn () => ['payment_method' => 'cash']);
    }
}