<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();

            // Relasi ke warehouse
            $table->foreignId('warehouse_id')
                  ->constrained('warehouses')
                  ->cascadeOnDelete();

            // Self relation (Area > Rack > Shelf > Bin)
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('locations')
                  ->cascadeOnDelete();

            // Tipe lokasi (Area, Rack, Shelf, Bin)
            $table->foreignId('location_type_id')
                  ->constrained('location_types')
                  ->restrictOnDelete();

            // Identitas lokasi
            $table->string('code', 100);
            $table->string('name', 150)->nullable();
            $table->text('description')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // Audit
            $table->timestamps();

            // Unique code per warehouse
            $table->unique(['warehouse_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
