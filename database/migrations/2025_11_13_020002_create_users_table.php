<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');

            // Role enum kept for fast auth checks (isAdmin(), isDriver(), etc.)
            // user_type_id is the proper FK mirror of this value
            $table->enum('role', ['admin', 'driver', 'customer'])->default('customer');

            // FK → user_types (admin / driver / customer)
            $table->foreignId('user_type_id')
                ->nullable()
                ->constrained('user_types')
                ->onDelete('set null');

            // FK → discount_types (senior, pwd, student, etc.)
            // NULL means no discount applied to this user
            $table->foreignId('discount_type_id')
                ->nullable()
                ->constrained('discount_types')
                ->nullOnDelete();

            $table->enum('status', ['active', 'blocked'])->default('active');

            $table->string('image_url')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};