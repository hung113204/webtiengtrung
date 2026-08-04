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
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE nguoi_dung MODIFY vai_tro VARCHAR(255) DEFAULT 'Học viên'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE nguoi_dung MODIFY vai_tro ENUM('Admin', 'Giảng viên', 'Học viên') DEFAULT 'Học viên'");
        });
    }
};
