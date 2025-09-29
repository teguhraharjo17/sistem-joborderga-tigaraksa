<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        addVendors(['amcharts', 'amcharts-maps', 'amcharts-stock']);

        // Ambil daftar tahun yang ada di laporan
        $years = DB::table('laporan_kerusakan')
            ->selectRaw("YEAR(created_at) as tahun")
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');

        $currentYear = request('year', now()->year);

        // Status Perbaikan
        $statusPerbaikan = DB::table('laporan_kerusakan_ga')
            ->whereYear('created_at', $currentYear)
            ->select('status_perbaikan', DB::raw('COUNT(*) as total'))
            ->groupBy('status_perbaikan')
            ->get();

        // Tren Bulanan
        $trenBulanan = DB::table('laporan_kerusakan')
            ->whereYear('created_at', $currentYear)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Lokasi Terbanyak Rusak
        $topLokasi = DB::table('laporan_kerusakan')
            ->whereYear('created_at', $currentYear)
            ->select('lokasi', DB::raw('COUNT(*) as total'))
            ->groupBy('lokasi')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Internal vs External
        $internalExternal = DB::table('laporan_kerusakan_ga')
            ->whereYear('created_at', $currentYear)
            ->select('internal_external', DB::raw('COUNT(*) as total'))
            ->groupBy('internal_external')
            ->get();

        if (request()->ajax()) {
            return response()->json(compact(
                'statusPerbaikan',
                'trenBulanan',
                'topLokasi',
                'internalExternal'
            ));
        }

        return view('pages.dashboards.index', compact(
            'years',
            'currentYear',
            'statusPerbaikan',
            'trenBulanan',
            'topLokasi',
            'internalExternal'
        ));
    }

}
