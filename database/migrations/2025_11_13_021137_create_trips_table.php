<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();

            $table->foreignId('route_id')
                ->nullable()
                ->constrained('routes')
                ->onDelete('set null');

            $table->foreignId('bus_id')
                ->nullable()
                ->constrained('buses')
                ->onDelete('set null');

            // Driver assigned to this trip
            $table->foreignId('driver_id')
                ->nullable()
                ->constrained('drivers')
                ->onDelete('set null');

            $table->string('trip_code')->unique()->nullable();
            $table->date('trip_date');

            // FIX: was time() — incompatible with model's datetime cast.
            // Using timestamp so Carbon works correctly.
            $table->timestamp('departure_time');
            $table->timestamp('arrival_time')->nullable();

            $table->integer('available_seats')->default(0);
            $table->decimal('fare', 10, 2)->nullable();

            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])
                ->default('scheduled');

            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};