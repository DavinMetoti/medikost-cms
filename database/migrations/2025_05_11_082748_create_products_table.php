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
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('sku')->unique();
                $table->string('barcode')->nullable()->index();
                $table->unsignedBigInteger('category_id')->nullable()->index();
                $table->unsignedBigInteger('supplier_id')->nullable()->index();
                $table->json('tag')->nullable();
                $table->text('description')->nullable();
                $table->integer('stock')->default(0);
                $table->integer('stock_minimum')->default(0);
                $table->decimal('price', 15, 2)->nullable();
                $table->string('unit')->default('pcs');
                $table->string('location')->nullable();
                $table->boolean('is_active')->default(true);
                $table->json('images')->nullable();
                $table->json('specifications')->nullable();
                $table->boolean('is_published')->default(false);
                $table->timestamps();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();

                // Foreign key constraints
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
                $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
                $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
