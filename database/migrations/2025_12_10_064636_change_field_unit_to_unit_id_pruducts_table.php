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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->after('price')->nullable()->index();
            $table->foreign('unit_id')->references('id')->on('unit_of_measurements')->onDelete('set null');
            $table->dropColumn('unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('unit')->after('price')->default('pcs');
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
        });
    }
};
