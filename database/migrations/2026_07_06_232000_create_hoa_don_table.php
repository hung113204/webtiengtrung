<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hoa_don', function (Blueprint $table) {
            $table->id();
            $table->string('ma_hoa_don')->unique();
            $table->unsignedBigInteger('id_dang_ky')->nullable();
            $table->unsignedBigInteger('id_nguoi_dung');
            $table->double('so_tien')->default(0);
            $table->string('phuong_thuc_thanh_toan')->default('Chuyển khoản');
            $table->string('ma_giao_dich')->nullable();
            $table->enum('trang_thai', ['Chưa thanh toán', 'Đã thanh toán', 'Đã hủy'])->default('Chưa thanh toán');
            $table->dateTime('ngay_thanh_toan')->nullable();
            $table->timestamps();

            // Set up foreign keys
            $table->foreign('id_dang_ky')
                  ->references('id')
                  ->on('dang_ky_khoa_hoc')
                  ->onDelete('cascade');

            $table->foreign('id_nguoi_dung')
                  ->references('id')
                  ->on('nguoi_dung')
                  ->onDelete('cascade');
        });

        // Backfill existing registrations to keep DB consistent
        $registrations = DB::table('dang_ky_khoa_hoc')->get();
        foreach ($registrations as $reg) {
            $course = DB::table('khoa_hoc')->where('id', $reg->id_khoa_hoc)->first();
            $price = $course ? ($course->gia_giam ?? $course->gia ?? 0) : 0;
            
            $invoiceStatus = 'Chưa thanh toán';
            if ($reg->trang_thai === 'Đã duyệt') {
                $invoiceStatus = 'Đã thanh toán';
            } elseif ($reg->trang_thai === 'Đã hủy') {
                $invoiceStatus = 'Đã hủy';
            }

            DB::table('hoa_don')->insert([
                'ma_hoa_don' => 'HD' . str_pad($reg->id, 6, '0', STR_PAD_LEFT),
                'id_dang_ky' => $reg->id,
                'id_nguoi_dung' => $reg->id_nguoi_dung,
                'so_tien' => $price,
                'phuong_thuc_thanh_toan' => 'Chuyển khoản',
                'trang_thai' => $invoiceStatus,
                'ngay_thanh_toan' => $reg->trang_thai === 'Đã duyệt' ? ($reg->ngay_dang_ky ?? now()) : null,
                'created_at' => $reg->created_at ?? now(),
                'updated_at' => $reg->updated_at ?? now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoa_don');
    }
};
