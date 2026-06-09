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

        Schema::create('bestelling-materiaal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materiaal_id')->constrained('materialen');
            $table->foreignId('bestelling_id')->constrained();
            $table->integer('quantity');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
