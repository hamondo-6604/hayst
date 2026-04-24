<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->onDelete('cascade');

            $table->decimal('amount', 10, 2);

            $table->enum('payment_method', [
                'cash', 'gcash', 'maya', 'card', 'bank_transfer', 'other'
            ])->default('cash');

            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])
                ->default('pending');

            // Reference from payment gateway (GCash ref, card auth code, etc.)
            $table->string('transaction_id')->nullable()->unique();
            $table->json('gateway_response')->nullable();  // raw gateway payload for audit

            $table->string('currency', 3)->default('PHP');
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};