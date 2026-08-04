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
        Schema::table('bai_hoc', function (Blueprint $table) {
            $table->enum('loai_dieu_kien', ['tu_dong', 'xem_video', 'kiem_tra'])->default('tu_dong')->after('luot_xem');
            $table->integer('phan_tram_video')->default(0)->after('loai_dieu_kien');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bai_hoc', function (Blueprint $table) {
            $table->dropColumn(['loai_dieu_kien', 'phan_tram_video']);
        });
    }
};
