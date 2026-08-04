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
        Schema::create('tinh_nangs', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de');
            $table->string('badge_text')->nullable();
            $table->text('mo_ta')->nullable();
            $table->json('danh_sach_bullet')->nullable(); // Lưu mảng các tính năng con
            $table->string('image_url')->nullable();
            $table->enum('vi_tri_anh', ['left', 'right'])->default('right');
            $table->string('stat_number')->nullable();
            $table->string('stat_label')->nullable();
            $table->text('stat_icon')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->integer('thu_tu')->default(0);
            $table->boolean('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tinh_nangs');
    }
};
