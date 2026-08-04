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
        Schema::table('hoi_thoai', function (Blueprint $table) {
            $table->dropColumn(['noi_dung', 'am_thanh']);
            $table->text('mo_ta')->nullable()->after('tieu_de');
            $table->integer('thu_tu')->default(0)->after('mo_ta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hoi_thoai', function (Blueprint $table) {
            $table->text('noi_dung')->nullable();
            $table->string('am_thanh')->nullable();
            $table->dropColumn(['mo_ta', 'thu_tu']);
        });
    }
};
