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
        Schema::create('thanh_tich', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->integer('tong_xp')->default(0); // Điểm kinh nghiệm
            $table->integer('chuoi_hoc')->default(0); // Streak học tập liên tục (ngày)
            $table->integer('tong_bai_da_hoc')->default(0); // Số lượng bài học đã hoàn thành
            $table->integer('tong_quiz')->default(0); // Số quiz/đề thi đã hoàn thành
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thanh_tich');
    }
};
