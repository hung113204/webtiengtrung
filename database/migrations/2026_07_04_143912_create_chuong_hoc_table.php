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
        Schema::create('chuong_hoc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_khoa_hoc');
            $table->string('ten_chuong');
            $table->string('slug')->unique();
            $table->boolean('trang_thai')->default(true);    // THÊM: true = xuất bản, false = nháp
            $table->integer('so_bai_hoc')->default(0);       // THÊM (cập nhật sau)
            $table->integer('thu_tu')->default(0);
            $table->text('mo_ta')->nullable();
            $table->timestamps();
            $table->foreign('id_khoa_hoc')->references('id')->on('khoa_hoc')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chuong_hoc');
    }
};
