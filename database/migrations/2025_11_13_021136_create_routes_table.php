<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('route_name')->unique();     // e.g. "Manila → Cebu"

            // Link to cities table properly
            $table->foreignId('origin_city_id')
                ->constrained('cities')
                ->onDelete('restrict');

            $table->foreignId('destination_city_id')
                ->constrained('cities')
                ->onDelete('restrict');

            $table->integer('distance_km')->nullable();
            $table->integer('estimated_duration_minutes')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};