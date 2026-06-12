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
        Schema::create('order_materials', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable(false)->constrained()->noActionOnDelete();
            $table->foreignId('material_id')->nullable(false)->constrained()->noActionOnDelete();
            $table->smallInteger('quantity')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_materials');
    }
};
