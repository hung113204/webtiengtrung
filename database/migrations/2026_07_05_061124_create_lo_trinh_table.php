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
        Schema::create('lo_trinh', function (Blueprint $table) {
            $table->id();
            $table->string('ten_lo_trinh', 255);
            $table->string('slug', 255)->unique();
            $table->string('mo_ta_ngan', 255)->nullable();
            $table->text('mo_ta')->nullable();
            $table->string('anh_bia')->nullable();
            $table->boolean('trang_thai')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lo_trinh');
    }
};
