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
        Schema::table('nguoi_dung', function (Blueprint $table) {
            $table->unsignedBigInteger('id_vai_tro')->nullable()->after('so_dien_thoai');
            $table->foreign('id_vai_tro')->references('id')->on('vai_tro')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {
            $table->dropForeign(['id_vai_tro']);
            $table->dropColumn('id_vai_tro');
        });
    }
};
