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
        Schema::create('phien_luyen_thi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_de_thi');
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->dateTime('thoi_gian_bat_dau')->nullable();
            $table->dateTime('thoi_gian_ket_thuc')->nullable();
            $table->decimal('tong_diem', 5, 2)->default(0);
            $table->integer('so_cau_dung')->default(0);
            $table->integer('so_cau_sai')->default(0);
            $table->enum('trang_thai', ['Đang làm', 'Hoàn thành', 'Hết thời gian'])->default('Đang làm');
            $table->timestamps();

            // Khóa ngoại
            $table->foreign('id_de_thi')->references('id')->on('de_thi')->onDelete('cascade');
            $table->foreign('id_nguoi_dung')->references('id')->on('nguoi_dung')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phien_luyen_thi');
    }
};
