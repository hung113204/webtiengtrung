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
        Schema::create('khoa_hoc', function (Blueprint $table) {
            $table->id();
            $table->string('ten_khoa_hoc');
            $table->string('slug')->unique();
            $table->string('mo_ta_ngan')->nullable();
            $table->longText('mo_ta')->nullable();
            $table->string('anh_bia')->nullable();
            $table->decimal('gia', 10, 2)->default(0);
            $table->unsignedBigInteger('id_cap_do_hsk');
            $table->integer('tong_bai_hoc')->default(0);
            $table->unsignedBigInteger('id_danh_muc_khoa_hoc');
            $table->integer('tong_thoi_gian')->default(0); // phút
            $table->boolean('noi_bat')->default(false);
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
            // Khóa ngoại
            $table->foreign('id_cap_do_hsk')
                  ->references('id')
                  ->on('cap_do_hsk')
                  ->onDelete('cascade');
                  
            // Khóa ngoại danh_muc_khoa_hoc đã được mở lại
            $table->foreign('id_danh_muc_khoa_hoc')
                ->references('id')
                ->on('danh_muc_khoa_hoc')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khoa_hoc');
    }
};
