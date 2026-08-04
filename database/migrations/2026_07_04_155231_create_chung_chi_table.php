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
        Schema::create('chung_chi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->unsignedBigInteger('id_cap_do_hsk');
            $table->date('ngay_cap'); // Ngày cấp chứng chỉ
            $table->string('ma_chung_chi')->unique()->nullable(); // Mã chứng chỉ (có thể null nếu chứng chỉ nội bộ không có mã)
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
            $table->foreign('id_cap_do_hsk')->references('id')->on('cap_do_hsk')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chung_chi');
    }
};
