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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();
            $table->text('address')->nullable();

            $table->decimal('distance_to_kariadi', 5, 2)
                ->nullable()
                ->index();

            $table->string('whatsapp')->nullable();
            $table->text('description')->nullable();

            $table->json('facilities')->nullable();
            $table->string('google_maps_link')->nullable();

            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_published')->default(false)->index();

            $table->json('images')->nullable();

            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->index(['is_active', 'is_published']);

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
