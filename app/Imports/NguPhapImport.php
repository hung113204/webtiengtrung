<?php

namespace App\Imports;

use App\Models\NguPhap;
use App\Models\BaiHoc;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class NguPhapImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts
{
    private $baiHocId;
    private $importedCount = 0;
    private $duplicateCount = 0;

    public function __construct($baiHocId = null)
    {
        $this->baiHocId = $baiHocId;
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
        // Lấy dữ liệu với các tên cột khác nhau để tránh lỗi
        $tieuDe = $row['tieu_de'] ?? null;
        $cauTruc = $row['cau_truc_mau'] ?? ($row['cau_truc'] ?? null);
        $giaiThich = $row['giai_thich_chi_tiet'] ?? ($row['giai_thich'] ?? null);
        $viDu = $row['cau_vi_du'] ?? ($row['vi_du'] ?? null);

        // Yêu cầu tối thiểu: tiêu đề, cấu trúc, giải thích
        if (empty($tieuDe) || empty($cauTruc) || empty($giaiThich)) {
            return null;
        }

        $baiHocId = $this->baiHocId;

        // Ưu tiên đọc từ cột bài học
        if (!empty($row['bai_hoc'])) {
            $baiHoc = BaiHoc::where('ten_bai_hoc', trim($row['bai_hoc']))->first();
            if ($baiHoc) {
                $baiHocId = $baiHoc->id;
            }
        }

        if (!$baiHocId) {
            return null;
        }

        // Kiểm tra trùng lặp
        $exists = NguPhap::where('tieu_de', $tieuDe)
                         ->where('id_bai_hoc', $baiHocId)
                         ->exists();

        if ($exists) {
            $this->duplicateCount++;
            return null;
        }

        $this->importedCount++;

        return new NguPhap([
            'id_bai_hoc' => $baiHocId,
            'tieu_de' => $tieuDe,
            'cau_truc' => $cauTruc,
            'giai_thich' => $giaiThich,
            'vi_du' => $viDu,
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
