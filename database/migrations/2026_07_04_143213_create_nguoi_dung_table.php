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
        Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->id();
            $table->string('ho_ten');
            $table->string('ten_dang_nhap')->unique();
            $table->string('email')->unique();
            $table->string('mat_khau');
            $table->string('anh_dai_dien')->nullable();
            $table->date('ngay_sinh')->nullable();
            $table->enum('gioi_tinh', ['Nam', 'Nữ', 'Khác'])->nullable();
            $table->string('so_dien_thoai', 20)->nullable();
            $table->enum('vai_tro', ['Admin', 'Giảng viên', 'Học viên'])->default('Học viên');
            $table->boolean('trang_thai')->default(true);
            $table->timestamp('email_xac_thuc_luc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguoi_dung');
    }
};
