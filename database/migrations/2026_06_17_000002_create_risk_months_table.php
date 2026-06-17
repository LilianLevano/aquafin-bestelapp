<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_months', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->noActionOnDelete();
            $table->foreignId('flood_risk_analysis_id')->constrained()->noActionOnDelete();
            $table->integer('year');
            $table->integer('month');
            $table->decimal('risk_value', 5, 2);
            $table->decimal('threshold', 5, 2);
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_months');
    }
};