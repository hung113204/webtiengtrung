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
        Schema::table('giai_doan_lo_trinh', function (Blueprint $table) {
            $table->string('icon_text', 50)->nullable()->after('id_lo_trinh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('giai_doan_lo_trinh', function (Blueprint $table) {
            $table->dropColumn('icon_text');
        });
    }
};
