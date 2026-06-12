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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable(false);
            $table->string('street')->nullable(false);
            $table->integer('house_number')->nullable(false);
            $table->string('city')->nullable(false);
            $table->string('postal_code')->nullable(false);
            $table->string('country_iso', 2)->nullable(false);
            $table->string('unit_number')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);
        });

        Schema::table('sites', function (Blueprint $table) {
            $table->foreignId('address_id')->nullable(false)->constrained()->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
