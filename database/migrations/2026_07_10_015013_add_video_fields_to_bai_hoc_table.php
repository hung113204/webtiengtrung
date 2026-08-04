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
            $table->integer('thoi_luong')->nullable()->after('video')->comment('Thời lượng video (giây)');
            $table->bigInteger('kich_thuoc')->nullable()->after('thoi_luong')->comment('Kích thước video (bytes)');
            $table->string('thumbnail')->nullable()->after('kich_thuoc');
            $table->string('hls_path')->nullable()->after('thumbnail')->comment('Đường dẫn file m3u8');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bai_hoc', function (Blueprint $table) {
            $table->dropColumn(['thoi_luong', 'kich_thuoc', 'thumbnail', 'hls_path']);
        });
    }
};
