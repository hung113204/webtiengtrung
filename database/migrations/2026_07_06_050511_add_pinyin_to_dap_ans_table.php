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
        Schema::table('dap_an', function (Blueprint $table) {
            $table->string('pinyin')->nullable()->after('noi_dung');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dap_an', function (Blueprint $table) {
            $table->dropColumn('pinyin');
        });
    }
};
