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
        Schema::dropIfExists('lo_trinh_khoa_hoc');

        Schema::create('giai_doan_lo_trinh', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lo_trinh');
            $table->string('ten_giai_doan', 255);
            $table->text('mo_ta')->nullable();
            $table->integer('thu_tu')->default(0);
            $table->timestamps();

            $table->foreign('id_lo_trinh')->references('id')->on('lo_trinh')->onDelete('cascade');
        });

        Schema::create('giai_doan_khoa_hoc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_giai_doan');
            $table->unsignedBigInteger('id_khoa_hoc');
            $table->integer('thu_tu')->default(0);

            $table->foreign('id_giai_doan')->references('id')->on('giai_doan_lo_trinh')->onDelete('cascade');
            $table->foreign('id_khoa_hoc')->references('id')->on('khoa_hoc')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giai_doan_khoa_hoc');
        Schema::dropIfExists('giai_doan_lo_trinh');

        Schema::create('lo_trinh_khoa_hoc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_lo_trinh');
            $table->unsignedBigInteger('id_khoa_hoc');
            $table->integer('thu_tu')->default(0);

            $table->foreign('id_lo_trinh')->references('id')->on('lo_trinh')->onDelete('cascade');
            $table->foreign('id_khoa_hoc')->references('id')->on('khoa_hoc')->onDelete('cascade');
        });
    }
};
