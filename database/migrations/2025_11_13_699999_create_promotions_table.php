<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();           // promo code e.g. SUMMER20
            $table->string('name');                     // display name
            $table->text('description')->nullable();

            $table->enum('discount_type', ['percent', 'fixed'])->default('percent');
            $table->decimal('discount_value', 10, 2);  // e.g. 20.00 = 20% or ₱20 off

            $table->decimal('minimum_fare', 10, 2)->nullable();   // min booking amount to apply
            $table->decimal('maximum_discount', 10, 2)->nullable(); // cap for percent discounts
            $table->string('image_url')->nullable();

            $table->integer('max_uses')->nullable();      // null = unlimited
            $table->integer('used_count')->default(0);
            $table->integer('max_uses_per_user')->default(1);

            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};