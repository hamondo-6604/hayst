<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('bus_types', function (Blueprint $table) {
      $table->id();
      $table->string('type_name');

      $table->foreignId('seat_layout_id')
        ->nullable()
        ->constrained('seat_layouts')
        ->onDelete('set null');

      $table->enum('status', ['active', 'inactive'])->default('active');
      $table->text('description')->nullable();
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('bus_types');
  }
};
