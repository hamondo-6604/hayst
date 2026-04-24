<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The existing bookings table has a single seat_id and seat_number column,
     * which only supports one seat per booking. This table is the proper
     * many-to-many pivot that lets one booking reserve multiple seats
     * (group bookings, family travel, etc.).
     *
     * The existing seat_id on bookings is kept as the "primary seat" for
     * backwards compatibility, but the booking_seats table is now the
     * authoritative source for which seats belong to a booking.
     */
    public function up(): void
    {
        Schema::create('booking_seats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->onDelete('cascade');

            $table->foreignId('seat_id')
                ->constrained('seats')
                ->onDelete('restrict'); // never orphan a seat assignment

            $table->foreignId('seat_type_id')
                ->nullable()
                ->constrained('seat_types')
                ->onDelete('set null');

            // Passenger details for this specific seat
            // (for group bookings each seat may have a different passenger)
            $table->string('passenger_name')->nullable();
            $table->string('passenger_type')->nullable(); // adult, child, infant
            $table->string('seat_number');                // denormalised for quick display

            // Per-seat fare (may differ if mixing seat types in one booking)
            $table->decimal('fare', 10, 2)->default(0.00);

            $table->enum('status', ['reserved', 'confirmed', 'cancelled'])
                ->default('reserved');

            $table->timestamps();

            // A seat can only be in one active booking per trip
            // (enforced at application level via booking flow, but indexed here)
            $table->unique(['booking_id', 'seat_id']);
            $table->index('seat_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_seats');
    }
};