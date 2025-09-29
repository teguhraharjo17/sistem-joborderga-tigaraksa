<?php

namespace App\Http\Controllers\ProgressKerja;

use App\Http\Controllers\Controller;
use App\Models\LaporanKerusakan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProgressKerjaController extends Controller
{
    public function index()
    {
        return view('pages.progresskerja.index');
    }

    public function data(Request $request)
    {
        $query = LaporanKerusakan::with('laporanGA')
            ->whereNotNull('ttd_dilaporkan_oleh')
            ->whereNotNull('ttd_diketahui_oleh')
            ->whereNotNull('ttd_diterima_oleh');
            
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $start = \Carbon\Carbon::createFromDate($request->tahun, $request->bulan, 1)->startOfDay();
            $end = \Carbon\Carbon::createFromDate($request->tahun, $request->bulan, 1)->endOfMonth()->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        $query->orderByDesc('created_at');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', fn($row) => $row->created_at->format('d F Y'))

            ->addColumn('rencana_tindakan_perbaikan', function ($row) {
                if ($row->laporanGA && $row->laporanGA->rencana_tindakan_perbaikan) {
                    return \Carbon\Carbon::parse($row->laporanGA->rencana_tindakan_perbaikan)->format('d F Y');
                }
                return '<span class="text-muted">Belum diisi</span>';
            })

            ->addColumn('selesai_perbaikan', function ($row) {
                if ($row->laporanGA && $row->laporanGA->selesai_perbaikan) {
                    return \Carbon\Carbon::parse($row->laporanGA->selesai_perbaikan)->format('d F Y');
                }
                return '<span class="text-muted">Belum diisi</span>';
            })

            ->addColumn('pic', fn($row) => $row->laporanGA && $row->laporanGA->pic
                ? e($row->laporanGA->pic)
                : '<span class="text-muted">Belum diisi</span>')

            ->addColumn('tindakan_perbaikan', fn($row) => $row->laporanGA && $row->laporanGA->tindakan_perbaikan
                ? e($row->laporanGA->tindakan_perbaikan)
                : '<span class="text-muted">Belum diisi</span>')

            ->addColumn('status_perbaikan', function ($row) {
                $status = optional($row->laporanGA)->status_perbaikan;
                return match ($status) {
                    'belum_mulai' => '<span class="badge bg-danger text-white">Belum Mulai</span>',
                    'progress'    => '<span class="badge bg-warning text-white">Progress</span>',
                    'selesai'     => '<span class="badge bg-success text-white">Selesai</span>',
                    default       => '<span class="badge bg-secondary text-white">-</span>',
                };
            })

            ->addColumn('bukti_upload', function ($row) {
                if ($row->laporanGA && $row->laporanGA->bukti_upload) {
                    $url = asset('storage/' . $row->laporanGA->bukti_upload);
                    return '<a href="' . $url . '" target="_blank">
                                <img src="' . $url . '" alt="Bukti" style="max-height: 60px; border-radius: 4px;">
                            </a>';
                }
                return '<span class="text-muted">-</span>';
            })

            ->rawColumns([
                'rencana_tindakan_perbaikan',
                'selesai_perbaikan',
                'pic',
                'tindakan_perbaikan',
                'status_perbaikan',
                'bukti_upload',
            ])
            ->make(true);
    }
}
