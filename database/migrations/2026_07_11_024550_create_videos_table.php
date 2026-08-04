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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('hash_id')->unique();
            $table->string('ten_video');
            $table->string('file_path')->nullable();
            $table->string('hls_path')->nullable();
            $table->integer('thoi_luong_giay')->nullable();
            $table->integer('kich_thuoc')->nullable();
            $table->enum('trang_thai', ['dang_cho', 'dang_xu_ly', 'hoan_thanh', 'loi'])->default('dang_cho');
            $table->integer('phan_tram')->default(0);
            $table->text('thong_bao_loi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
