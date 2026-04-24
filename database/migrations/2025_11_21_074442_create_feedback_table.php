<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('booking_id')
                ->nullable()
                ->constrained('bookings')
                ->onDelete('set null');

            $table->foreignId('trip_id')
                ->nullable()
                ->constrained('trips')
                ->onDelete('set null');

            $table->unsignedTinyInteger('rating');       // 1–5
            $table->string('subject')->nullable();
            $table->text('comment')->nullable();

            $table->enum('type', ['trip', 'driver', 'bus', 'general'])->default('general');
            $table->enum('status', ['pending', 'reviewed', 'resolved'])->default('pending');

            $table->text('admin_reply')->nullable();
            $table->timestamp('replied_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};