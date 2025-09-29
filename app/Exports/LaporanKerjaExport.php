<?php

namespace App\Exports;

use App\Models\LaporanKerusakan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanKerjaExport
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan = null, $tahun = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function export()
    {
        $query = LaporanKerusakan::with('laporanGA')
            ->whereNotNull('ttd_dilaporkan_oleh')
            ->whereNotNull('ttd_diketahui_oleh')
            ->whereNotNull('ttd_diterima_oleh');

        if ($this->bulan && $this->tahun) {
            $start = Carbon::createFromDate($this->tahun, $this->bulan, 1)->startOfDay();
            $end = Carbon::createFromDate($this->tahun, $this->bulan, 1)->endOfMonth()->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        $data = $query->orderByDesc('created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'No', 'Nama', 'PIC', 'Tanggal', 'Barang', 'Lokasi', 'Uraian Masalah',
            'Rencana Tindakan', 'Selesai', 'Tindakan', 'Status',
            'TTD Dilaporkan', 'TTD Diketahui', 'TTD Diterima',
            'TTD Dikerjakan', 'TTD Dikontrol', 'Bukti Upload'
        ];

        $sheet->fromArray($headers, null, 'A1');
        $rowNum = 2;

        foreach ($data as $index => $row) {
            $sheet->setCellValue("A{$rowNum}", $index + 1);
            $sheet->setCellValue("B{$rowNum}", $row->nama);
            $sheet->setCellValue("C{$rowNum}", optional($row->laporanGA)->pic ?? '-');
            $sheet->setCellValue("D{$rowNum}", $row->created_at->format('d-m-Y'));
            $sheet->setCellValue("E{$rowNum}", $row->jenis_barang);
            $sheet->setCellValue("F{$rowNum}", $row->lokasi);
            $sheet->setCellValue("G{$rowNum}", $row->uraian_masalah);
            $sheet->setCellValue("H{$rowNum}", optional($row->laporanGA)->rencana_tindakan_perbaikan ?? '-');
            $sheet->setCellValue("I{$rowNum}", optional($row->laporanGA)->selesai_perbaikan ?? '-');
            $sheet->setCellValue("J{$rowNum}", optional($row->laporanGA)->tindakan_perbaikan ?? '-');
            $sheet->setCellValue("K{$rowNum}", optional($row->laporanGA)->status_perbaikan ?? '-');

            $images = [
                'L' => $row->ttd_dilaporkan_oleh,
                'M' => $row->ttd_diketahui_oleh,
                'N' => $row->ttd_diterima_oleh,
                'O' => optional($row->laporanGA)->ttd_dikerjakan_oleh,
                'P' => optional($row->laporanGA)->ttd_dikontrol_oleh,
                'Q' => optional($row->laporanGA)->bukti_upload,
            ];

            foreach ($images as $col => $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    $drawing = new Drawing();
                    $drawing->setName("Img");
                    $drawing->setDescription("Image");
                    $drawing->setPath(storage_path('app/public/' . $path));
                    $drawing->setHeight(60);
                    $drawing->setCoordinates("{$col}{$rowNum}");
                    $drawing->setOffsetX(10);
                    $drawing->setOffsetY(10);
                    $drawing->setWorksheet($sheet);
                } else {
                    $sheet->setCellValue("{$col}{$rowNum}", '-');
                }
            }

            $rowNum++;
        }

        foreach (range('A', 'Q') as $col) {
            if (in_array($col, ['L', 'M', 'N', 'O', 'P', 'Q'])) {
                $sheet->getColumnDimension($col)->setWidth(25);
            } else {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $sheet->getStyle("{$col}1:{$col}{$rowNum}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                ->setVertical(Alignment::VERTICAL_CENTER);

            $sheet->getStyle("{$col}1:{$col}{$rowNum}")
                ->getBorders()->getAllBorders()
                ->setBorderStyle(Border::BORDER_THIN);
        }

        for ($i = 2; $i < $rowNum; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(80);
        }

        $filename = 'Laporan_Kerja_GA_' . now()->format('Ymd_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}