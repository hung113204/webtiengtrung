<?php

namespace App\Imports;

use App\Models\KhoaHocYeuCau;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

class KhoaHocYeuCauImport implements ToModel, WithHeadingRow, WithChunkReading, WithBatchInserts, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $khoaHocId;
    private $importedCount = 0;
    private $duplicateCount = 0;

    public function __construct($khoaHocId)
    {
        $this->khoaHocId = $khoaHocId;
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
        $noiDung = $row['noi_dung'] ?? null;
        $thuTu = $row['thu_tu'] ?? null;

        if (empty($noiDung)) {
            return null;
        }

        // Kiểm tra trùng lặp
        $exists = KhoaHocYeuCau::where('khoa_hoc_id', $this->khoaHocId)
                              ->where('noi_dung', $noiDung)
                              ->exists();

        if ($exists) {
            $this->duplicateCount++;
            return null;
        }

        if (empty($thuTu)) {
            $max = KhoaHocYeuCau::where('khoa_hoc_id', $this->khoaHocId)->max('thu_tu') ?? 0;
            $thuTu = $max + 1;
        }

        $this->importedCount++;

        return new KhoaHocYeuCau([
            'khoa_hoc_id' => $this->khoaHocId,
            'noi_dung' => $noiDung,
            'thu_tu' => $thuTu,
        ]);
    }

    public function rules(): array
    {
        return [
            'noi_dung' => 'required|string|max:255',
            'thu_tu' => 'nullable|integer|min:1',
        ];
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
