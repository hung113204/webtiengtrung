<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\NguoiDung;
use App\Models\KhoaHoc;
use App\Models\YeuThichKhoaHoc;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class YeuThichKhoaHocTest extends TestCase
{
    // Cẩn thận với RefreshDatabase nếu chưa setup DB sqlite cho testing
    // Ở đây ta dùng database transaction để rollback sau khi test
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    private function createDummyUser()
    {
        return NguoiDung::first();
    }

    private function createDummyCourse()
    {
        return KhoaHoc::create([
            'ten_khoa_hoc' => 'Khóa học test ' . Str::random(5),
            'slug' => 'khoa-hoc-test-' . Str::random(5),
            'mieu_ta' => 'Test',
            'gia' => 100000,
            'id_danh_muc' => 1, // Assume 1 exists or is nullable, if not might fail
            'trang_thai' => 1,
        ]);
    }

    /** @test */
    public function chua_dang_nhap_thi_khong_the_yeu_thich_khoa_hoc()
    {
        $response = $this->postJson("/khoa-hoc/999/yeu-thich");

        $response->assertStatus(401);
    }

    /** @test */
    public function user_co_the_them_khoa_hoc_vao_danh_sach_yeu_thich()
    {
        $user = $this->createDummyUser();
        $khoaHoc = KhoaHoc::first();

        if (!$khoaHoc || !$user) {
            $this->markTestSkipped('Không có khóa học hoặc user nào trong Database để test.');
        }

        $this->actingAs($user);

        $response = $this->postJson("/khoa-hoc/{$khoaHoc->id}/yeu-thich");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'status' => 'added'
                 ]);

        $this->assertDatabaseHas('yeu_thich_khoa_hocs', [
            'id_nguoi_dung' => $user->id,
            'id_khoa_hoc' => $khoaHoc->id,
        ]);
    }

    /** @test */
    public function user_co_the_bo_khoa_hoc_khoi_danh_sach_yeu_thich()
    {
        $user = $this->createDummyUser();
        $khoaHoc = KhoaHoc::first();

        if (!$khoaHoc || !$user) {
            $this->markTestSkipped('Không có khóa học hoặc user nào trong Database để test.');
        }

        // Thêm vào danh sách yêu thích trước
        YeuThichKhoaHoc::create([
            'id_nguoi_dung' => $user->id,
            'id_khoa_hoc' => $khoaHoc->id,
        ]);

        $this->actingAs($user);

        // Bấm lần nữa để bỏ yêu thích
        $response = $this->postJson("/khoa-hoc/{$khoaHoc->id}/yeu-thich");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'status' => 'removed'
                 ]);

        $this->assertDatabaseMissing('yeu_thich_khoa_hocs', [
            'id_nguoi_dung' => $user->id,
            'id_khoa_hoc' => $khoaHoc->id,
        ]);
    }
}
