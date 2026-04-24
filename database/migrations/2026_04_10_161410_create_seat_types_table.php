<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seat_types', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();           // economy, business, sleeper, etc.
            $table->string('display_name');             // "Economy Class", "Business Class"
            $table->text('description')->nullable();

            // Pricing modifier — multiplied against the base trip fare
            // e.g. 1.00 = same as base, 1.5 = 50% more expensive than economy
            $table->decimal('price_multiplier', 5, 2)->default(1.00);

            // Visual / display
            $table->string('icon')->nullable();         // fa icon name e.g. "fas fa-couch"
            $table->string('color_hex', 7)->nullable(); // e.g. #b8912a

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_types');
    }
};