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
        Schema::create('de_thi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bai_hoc')->nullable(); // FK đến bai_hoc (có thể NULL)
            $table->string('ten_de_thi'); // Tên đề thi
            $table->text('mo_ta')->nullable(); // Mô tả đề
            $table->integer('thoi_gian_lam')->default(0); // Thời gian làm (phút)
            $table->integer('so_cau')->default(0); // Số câu hỏi
            $table->integer('diem_dat')->default(0); // Điểm đạt
            $table->enum('muc_do', ['Dễ', 'Trung bình', 'Khó'])->default('Trung bình'); // Mức độ
            $table->enum('loai_de', ['Luyện tập', 'Thi thử', 'Kiểm tra'])->default('Luyện tập'); // Loại đề
            $table->boolean('trang_thai')->default(true); // Hoạt động / Ẩn
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_bai_hoc')->references('id')->on('bai_hoc')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('de_thi');
    }
};
