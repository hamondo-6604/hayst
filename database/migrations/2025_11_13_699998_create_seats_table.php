<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('seats', function (Blueprint $table) {
      $table->id();

      $table->foreignId('bus_id')
        ->constrained('buses')
        ->onDelete('cascade');

      // Example: A1, B2, 01A, 12C, etc.
      $table->string('seat_number');

      // NULL = inherits bus.default_seat_type
      $table->string('seat_type')->nullable();

      $table->enum('status', ['available', 'booked', 'blocked'])
        ->default('available');

      $table->timestamps();

      $table->unique(['bus_id', 'seat_number']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('seats');
  }
};
