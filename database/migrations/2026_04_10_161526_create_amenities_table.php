<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Master catalogue of amenities
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();           // wifi, ac, usb_charging, reclining_seat
            $table->string('display_name');             // "WiFi", "Air Conditioning", "USB Charging"
            $table->string('icon')->nullable();         // "fas fa-wifi"
            $table->text('description')->nullable();

            $table->enum('category', [
                'comfort',      // reclining seat, legroom, blanket
                'connectivity', // wifi, usb, power outlet
                'safety',       // seatbelt, cctv, fire extinguisher
                'service',      // meal, bottled water, restroom
                'entertainment',// tv, music
            ])->default('comfort');

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pivot: which amenities does each bus have
        Schema::create('bus_amenities', function (Blueprint $table) {
            $table->foreignId('bus_id')
                ->constrained('buses')
                ->onDelete('cascade');

            $table->foreignId('amenity_id')
                ->constrained('amenities')
                ->onDelete('cascade');

            // Some amenities vary by seat type on the same bus
            // e.g. WiFi only in Business, not Economy
            $table->foreignId('seat_type_id')
                ->nullable()
                ->constrained('seat_types')
                ->onDelete('set null');

            // Optional note e.g. "USB at every seat", "Blanket on request"
            $table->string('note')->nullable();

            $table->primary(['bus_id', 'amenity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_amenities');
        Schema::dropIfExists('amenities');
    }
};