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
        Schema::create('ngu_phap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bai_hoc');
            $table->string('tieu_de'); // Tên điểm ngữ pháp
            $table->string('cau_truc'); // Cấu trúc ngữ pháp (VD: S + V + O)
            $table->text('giai_thich'); // Giải thích chi tiết cách dùng
            $table->text('vi_du')->nullable(); // Các ví dụ minh họa
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_bai_hoc')->references('id')->on('bai_hoc')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ngu_phap');
    }
};
