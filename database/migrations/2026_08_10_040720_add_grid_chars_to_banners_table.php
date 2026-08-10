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
        Schema::table('banners', function (Blueprint $table) {
            $table->string('grid_char_1', 10)->default('你')->nullable();
            $table->string('grid_char_2', 10)->default('好')->nullable();
            $table->string('grid_char_3', 10)->default('学')->nullable();
            $table->string('grid_char_4', 10)->default('中')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['grid_char_1', 'grid_char_2', 'grid_char_3', 'grid_char_4']);
        });
    }
};
