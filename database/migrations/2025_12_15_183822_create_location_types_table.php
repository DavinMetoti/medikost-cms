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
        Schema::create('location_types', function (Blueprint $table) {
            $table->id();

            // Identifier
            $table->string('code', 50)->unique();
            $table->string('name', 100);

            // Hirarki level
            $table->unsignedInteger('level_order');

            // Status
            $table->boolean('is_active')->default(true);

            // Audit
            $table->timestamps();

            // Optional index for performance
            $table->index('level_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_types');
    }
};
