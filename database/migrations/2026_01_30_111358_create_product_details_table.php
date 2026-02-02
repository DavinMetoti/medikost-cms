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
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id')->index();

            $table->string('room_name');
            $table->decimal('price', 15, 2)->index();

            $table->enum('status', ['kosong', 'isi'])
                ->default('kosong')
                ->index();

            $table->integer('room_size')->nullable();
            $table->json('facilities')->nullable();
            $table->text('description')->nullable();

            $table->json('images')->nullable();

            $table->boolean('is_active')->default(true)->index();

            $table->timestamps();

            $table->index(['product_id', 'status']);
            $table->index(['product_id', 'is_active']);

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_details');
    }
};
