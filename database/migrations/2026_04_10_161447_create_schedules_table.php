<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('route_id')
                ->constrained('routes')
                ->onDelete('cascade');

            $table->foreignId('bus_id')
                ->nullable()
                ->constrained('buses')
                ->onDelete('set null');

            $table->foreignId('driver_id')
                ->nullable()
                ->constrained('drivers')
                ->onDelete('set null');

            $table->string('schedule_code')->unique()->nullable(); // "SCH-MNL-BAG-0600"

            // Days of the week this schedule runs (stored as JSON array)
            // e.g. ["mon","wed","fri"] or ["daily"]
            $table->json('days_of_week');

            $table->time('departure_time');
            $table->time('arrival_time')->nullable();

            $table->decimal('fare', 10, 2)->nullable();

            // Date range this schedule is valid for
            $table->date('valid_from');
            $table->date('valid_until')->nullable();    // null = open-ended

            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['route_id', 'status']);
            $table->index('valid_from');
        });

        // Add schedule_id to trips so each trip knows which schedule generated it
        Schema::table('trips', function (Blueprint $table) {
            $table->foreignId('schedule_id')
                ->nullable()
                ->after('route_id')
                ->constrained('schedules')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropColumn('schedule_id');
        });

        Schema::dropIfExists('schedules');
    }
};