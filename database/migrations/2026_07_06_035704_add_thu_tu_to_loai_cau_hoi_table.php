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
        Schema::table('loai_cau_hoi', function (Blueprint $table) {
            $table->integer('thu_tu')->default(0)->after('ten_loai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loai_cau_hoi', function (Blueprint $table) {
            $table->dropColumn('thu_tu');
        });
    }
};
