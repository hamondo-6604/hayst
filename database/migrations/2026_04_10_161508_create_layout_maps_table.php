<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The existing seat_layouts table has a `layout_map` JSON column that stores
     * the entire grid as a blob. This table breaks that out into individual rows —
     * one row per seat cell in the grid — so you can query, filter, and update
     * individual cells without deserialising the whole JSON.
     *
     * The JSON column on seat_layouts is kept for backwards compatibility and
     * fast rendering (it's the denormalised cache), but the source of truth
     * for the grid definition is now this table.
     */
    public function up(): void
    {
        Schema::create('layout_maps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('seat_layout_id')
                ->constrained('seat_layouts')
                ->onDelete('cascade');

            $table->foreignId('seat_type_id')
                ->nullable()
                ->constrained('seat_types')
                ->onDelete('set null');

            // Grid position
            $table->unsignedSmallInteger('row_number');     // 1-based row index
            $table->unsignedSmallInteger('column_number');  // 1-based column index
            $table->string('seat_label');                   // e.g. "1A", "3C", "AISLE"

            $table->enum('cell_type', [
                'seat',     // a normal bookable seat
                'aisle',    // walkway — not bookable
                'empty',    // structural gap (e.g. over wheel arch)
                'driver',   // driver's seat
                'stairs',   // for double-deck buses
            ])->default('seat');

            // Whether this specific cell is available for booking in the layout template
            // (individual trip seat status lives on the `seats` table)
            $table->boolean('is_bookable')->default(true);

            $table->timestamps();

            $table->unique(['seat_layout_id', 'row_number', 'column_number']);
            $table->index(['seat_layout_id', 'cell_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layout_maps');
    }
};