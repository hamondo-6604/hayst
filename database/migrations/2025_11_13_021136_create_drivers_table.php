<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();

            // A driver IS a user (role = 'driver')
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('license_number')->unique();
            $table->date('license_expiry');
            $table->string('license_photo')->nullable();

            $table->integer('experience_years')->nullable();
            $table->string('contact_number')->nullable();
            $table->text('address')->nullable();
            $table->string('image_url')->nullable();

            $table->enum('status', ['available', 'on_trip', 'off_duty', 'suspended'])
                ->default('available');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};