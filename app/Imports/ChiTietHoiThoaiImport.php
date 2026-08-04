<?php

namespace App\Imports;

use App\Models\ChiTietHoiThoai;
use App\Models\HoiThoai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ChiTietHoiThoaiImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $hoiThoaiId;
    private $importedCount = 0;
    private $duplicateCount = 0;

    public function __construct($hoiThoaiId)
    {
        $this->hoiThoaiId = $hoiThoaiId;
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getDuplicateCount()
    {
        return $this->duplicateCount;
    }

    public function model(array $row)
    {
        // Nhận diện cột theo nhiều tên khả thi
        $nhanVat = $row['nhan_vat'] ?? null;
        $thuTu = $row['thu_tu'] ?? ($row['stt'] ?? null);
        $tiengTrung = $row['tieng_trung'] ?? ($row['han_tu'] ?? ($row['noi_dung'] ?? null));
        $pinyin = $row['pinyin'] ?? ($row['phien_am'] ?? null);
        $tiengViet = $row['tieng_viet'] ?? ($row['nghia_tieng_viet'] ?? ($row['y_nghia'] ?? null));
        
        // Xử lý cột tên file âm thanh
        $fileGhiAm = $row['file_ghi_am'] ?? ($row['am_thanh'] ?? ($row['audio'] ?? null));
        $amThanhPath = null;
        if (!empty($fileGhiAm)) {
            // Nếu có tên file, giả định file được lưu trong thư mục hoi_thoai/audio/
            $amThanhPath = 'hoi_thoai/audio/' . trim($fileGhiAm);
        }

        // Yêu cầu tối thiểu: nội dung tiếng trung
        if (empty($tiengTrung)) {
            return null;
        }

        if (!$this->hoiThoaiId) {
            return null;
        }

        // Tự động tính số thứ tự nếu không có
        if (empty($thuTu)) {
            $maxThuTu = ChiTietHoiThoai::where('id_hoi_thoai', $this->hoiThoaiId)->max('thu_tu');
            $thuTu = $maxThuTu ? $maxThuTu + 1 : 1;
        }

        // Kiểm tra trùng lặp (nếu cùng nội dung tiếng trung và cùng nhóm hội thoại)
        $exists = ChiTietHoiThoai::where('noi_dung_tieng_trung', $tiengTrung)
                         ->where('id_hoi_thoai', $this->hoiThoaiId)
                         ->exists();

        if ($exists) {
            $this->duplicateCount++;
            return null;
        }

        $this->importedCount++;

        return new ChiTietHoiThoai([
            'id_hoi_thoai' => $this->hoiThoaiId,
            'nhan_vat' => $nhanVat,
            'noi_dung_tieng_trung' => $tiengTrung,
            'pinyin' => $pinyin,
            'nghia_tieng_viet' => $tiengViet,
            'thu_tu' => $thuTu,
            'am_thanh' => $amThanhPath,
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
