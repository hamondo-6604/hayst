<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('buses', function (Blueprint $table) {
      $table->id();
      $table->string('bus_number')->unique();
      $table->string('bus_name');

      $table->foreignId('bus_type_id')
        ->constrained('bus_types')
        ->onDelete('cascade');

      $table->foreignId('seat_layout_id')
        ->constrained('seat_layouts')
        ->onDelete('cascade');

      $table->integer('total_seats');

      // ⭐ Hybrid seat-type support
      $table->string('default_seat_type')->nullable();

      $table->string('bus_img')->nullable();
      $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('buses');
  }
};
