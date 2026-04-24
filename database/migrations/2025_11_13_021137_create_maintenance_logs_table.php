<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bus_id')
                ->constrained('buses')
                ->onDelete('cascade');

            // Who logged/performed the maintenance
            $table->foreignId('logged_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->string('title');
            $table->text('description')->nullable();

            $table->enum('type', ['preventive', 'corrective', 'emergency'])
                ->default('preventive');

            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])
                ->default('scheduled');

            $table->date('maintenance_date');
            $table->date('completed_date')->nullable();

            $table->decimal('cost', 10, 2)->nullable();
            $table->string('performed_by')->nullable();   // external mechanic name / shop
            $table->text('parts_replaced')->nullable();
            $table->date('next_maintenance_due')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};