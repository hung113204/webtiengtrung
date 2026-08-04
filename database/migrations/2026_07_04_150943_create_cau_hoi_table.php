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
        Schema::create('cau_hoi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bai_hoc');
            $table->unsignedBigInteger('id_loai_cau_hoi');
            $table->text('noi_dung'); // Nội dung câu hỏi
            $table->string('hinh_anh')->nullable(); // Hình ảnh đính kèm câu hỏi
            $table->string('am_thanh')->nullable(); // File nghe đính kèm câu hỏi
            $table->string('video')->nullable(); // File video đính kèm câu hỏi
            $table->text('giai_thich')->nullable(); // Giải thích đáp án sau khi làm xong
            $table->enum('muc_do', ['Dễ', 'Trung bình', 'Khó'])->default('Trung bình'); // Mức độ câu hỏi
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_bai_hoc')->references('id')->on('bai_hoc')->onDelete('cascade');
            $table->foreign('id_loai_cau_hoi')->references('id')->on('loai_cau_hoi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cau_hoi');
    }
};
