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
        Schema::table('luyen_viet', function (Blueprint $table) {
            $table->string('pinyin')->nullable()->after('chu_han');
            $table->string('nghia')->nullable()->after('pinyin');
            $table->integer('so_net')->nullable()->after('nghia');
            $table->string('bo_thu')->nullable()->after('so_net');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('luyen_viet', function (Blueprint $table) {
            $table->dropColumn(['pinyin', 'nghia', 'so_net', 'bo_thu']);
        });
    }
};
