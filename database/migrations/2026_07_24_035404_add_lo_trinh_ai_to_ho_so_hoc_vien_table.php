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
        Schema::table('ho_so_hoc_vien', function (Blueprint $table) {
            $table->json('lo_trinh_ai')->nullable()->after('muc_tieu_hoc_tap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ho_so_hoc_vien', function (Blueprint $table) {
            $table->dropColumn('lo_trinh_ai');
        });
    }
};
