<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('booking_confirmed', 'booking_cancelled', 'payment_received', 'trip_reminder', 'trip_update', 'promotion', 'general', 'new_user', 'new_driver') DEFAULT 'general'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('booking_confirmed', 'booking_cancelled', 'payment_received', 'trip_reminder', 'trip_update', 'promotion', 'general') DEFAULT 'general'");
    }
};
