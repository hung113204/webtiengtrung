<?php

namespace Tests\Feature\Admin;

use App\Models\CapDoHSK;
use App\Models\DanhMucKhoaHoc;
use App\Models\KhoaHoc;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KhoaHocVideoTest extends TestCase
{
    use DatabaseTransactions;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_create_khoa_hoc_with_video_url()
    {
        $danhMuc = DanhMucKhoaHoc::firstOrCreate(['slug' => 'test-dm'], ['ten_danh_muc' => 'Test DM', 'trang_thai' => 1]);
        $capDo = CapDoHSK::firstOrCreate(['slug' => 'hsk-test'], ['ten_cap_do' => 'HSK Test', 'thu_tu' => 99]);

        $khoaHoc = KhoaHoc::create([
            'ten_khoa_hoc' => 'Khóa học Test Video URL',
            'slug' => 'khoa-hoc-test-video-url',
            'id_cap_do_hsk' => $capDo->id,
            'id_danh_muc_khoa_hoc' => $danhMuc->id,
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'gia' => 100000,
            'trang_thai' => 1,
        ]);

        $this->assertDatabaseHas('khoa_hoc', [
            'id' => $khoaHoc->id,
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        ]);
    }
}
