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
        Schema::create('chi_tiet_luyen_thi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_phien_luyen_thi');
            $table->unsignedBigInteger('id_cau_hoi');
            $table->unsignedBigInteger('id_dap_an')->nullable(); // Đáp án trắc nghiệm học viên chọn
            $table->text('dap_an_tu_luan')->nullable(); // Đáp án tự luận học viên nhập
            $table->boolean('dung')->default(false); // Kết quả câu này đúng hay sai
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_phien_luyen_thi')->references('id')->on('phien_luyen_thi')->onDelete('cascade');
            $table->foreign('id_cau_hoi')->references('id')->on('cau_hoi')->onDelete('cascade');
            $table->foreign('id_dap_an')->references('id')->on('dap_an')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_luyen_thi');
    }
};
