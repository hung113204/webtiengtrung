<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('danh_muc_khoa_hoc', function (Blueprint $table) {
            // Thêm cột parent_id sau cột id
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');

            // Foreign key tự tham chiếu (self-referencing)
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('danh_muc_khoa_hoc')
                  ->onDelete('set null'); // Xóa cha → con trở thành root
        });
    }

    public function down(): void
    {
        Schema::table('danh_muc_khoa_hoc', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
