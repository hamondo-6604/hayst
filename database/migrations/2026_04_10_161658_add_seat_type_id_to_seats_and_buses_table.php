<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The seats table currently stores seat type as a plain string column.
     * This migration adds a proper FK to seat_types so fare multipliers
     * and type metadata are properly linked.
     *
     * The original seat_type string column is kept for backwards compatibility.
     */
    public function up(): void
    {
        Schema::table('seats', function (Blueprint $table) {
            $table->foreignId('seat_type_id')
                ->nullable()
                ->after('seat_type')
                ->constrained('seat_types')
                ->onDelete('set null');
        });

        // Also add seat_type_id to buses.default_seat_type as a proper FK
        Schema::table('buses', function (Blueprint $table) {
            $table->foreignId('default_seat_type_id')
                ->nullable()
                ->after('default_seat_type')
                ->constrained('seat_types')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropForeign(['default_seat_type_id']);
            $table->dropColumn('default_seat_type_id');
        });

        Schema::table('seats', function (Blueprint $table) {
            $table->dropForeign(['seat_type_id']);
            $table->dropColumn('seat_type_id');
        });
    }
};