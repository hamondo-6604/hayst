<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('trip_id')
                ->constrained('trips')
                ->onDelete('restrict');   // never silently delete a trip with bookings

            $table->foreignId('seat_id')
                ->nullable()
                ->constrained('seats')
                ->onDelete('set null');

            // Promo applied to this booking
            $table->foreignId('promotion_id')
                ->nullable()
                ->constrained('promotions')
                ->onDelete('set null');

            $table->string('seat_number')->nullable();   // denormalised for quick display

            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])
                ->default('pending');

            // Financials
            $table->decimal('base_fare', 10, 2)->default(0.00);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('amount_paid', 10, 2)->default(0.00);

            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])
                ->default('unpaid');

            $table->string('booking_reference')->unique();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};