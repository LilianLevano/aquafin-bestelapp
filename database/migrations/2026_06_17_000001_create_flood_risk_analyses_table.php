<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flood_risk_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->noActionOnDelete();
            $table->integer('year');
            $table->integer('month');
            $table->decimal('min_risk', 5, 2)->nullable();
            $table->decimal('max_risk', 5, 2)->nullable();
            $table->decimal('avg_risk', 5, 2)->nullable();
            $table->decimal('total_precipitation', 8, 2)->nullable();
            $table->decimal('avg_humidity', 5, 2)->nullable();
            $table->string('season');
            $table->boolean('is_extreme')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flood_risk_analyses');
    }
};