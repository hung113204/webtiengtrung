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
        Schema::create('ho_so_hoc_vien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_nguoi_dung')->constrained('nguoi_dung')->onDelete('cascade');
            $table->string('trinh_do_hien_tai')->nullable()->comment('VD: Mới bắt đầu, Trung cấp, Nâng cao');
            $table->string('muc_tieu_hoc_tap')->nullable()->comment('VD: Luyện thi HSK, Giao tiếp, Công việc');
            $table->integer('muc_tieu_hsk')->nullable()->comment('Mục tiêu cấp độ HSK (1-6)');
            $table->string('thoi_gian_hoc_du_kien')->nullable()->comment('Thời gian dự kiến');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ho_so_hoc_vien');
    }
};
