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
        Schema::table('de_thi', function (Blueprint $table) {
            $table->dropColumn('muc_do');
            $table->unsignedBigInteger('id_muc_do')->nullable()->after('diem_dat');
            $table->foreign('id_muc_do')->references('id')->on('muc_dos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('de_thi', function (Blueprint $table) {
            $table->dropForeign(['id_muc_do']);
            $table->dropColumn('id_muc_do');
            $table->enum('muc_do', ['Dễ', 'Trung bình', 'Khó'])->default('Trung bình');
        });
    }
};
