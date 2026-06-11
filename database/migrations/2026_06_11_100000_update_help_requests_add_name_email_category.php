<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('help_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('name')->nullable()->after('id');
            $table->string('email')->nullable()->after('name');
            $table->string('category')->nullable()->after('email');
            $table->string('title')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('help_requests', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'category']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->string('title')->nullable(false)->change();
        });
    }
};
