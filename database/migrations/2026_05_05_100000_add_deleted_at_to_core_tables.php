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
        Schema::table('cities', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('routes', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('buses', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('bus_types', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('routes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('trips', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('buses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('bus_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
