<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('seat_layouts', function (Blueprint $table) {
      $table->id();
      $table->string('layout_name');
      $table->integer('total_rows');
      $table->integer('total_columns');
      $table->integer('capacity')->nullable();

      // JSON structure of seats
      $table->json('layout_map')->nullable();

      $table->string('status')->default('active');
      $table->text('description')->nullable();
      $table->timestamps();

      $table->unique(['layout_name', 'total_rows', 'total_columns']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('seat_layouts');
  }
};
