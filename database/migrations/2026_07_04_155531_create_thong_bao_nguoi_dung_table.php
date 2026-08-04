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
        Schema::create('thong_bao_nguoi_dung', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_thong_bao');
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->boolean('da_doc')->default(false); // Đánh dấu đã đọc hay chưa
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_thong_bao')->references('id')->on('thong_bao')->onDelete('cascade');
            $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_bao_nguoi_dung');
    }
};
