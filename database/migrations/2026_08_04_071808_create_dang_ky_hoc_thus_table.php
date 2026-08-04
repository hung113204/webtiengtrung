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
        Schema::create('dang_ky_hoc_thus', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->tinyInteger('trang_thai')->default(0)->comment('0: Chưa liên hệ, 1: Đã liên hệ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dang_ky_hoc_thus');
    }
};
