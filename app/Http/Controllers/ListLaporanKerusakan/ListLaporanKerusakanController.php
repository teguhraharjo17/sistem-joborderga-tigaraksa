<?php

namespace App\Http\Controllers\ListLaporanKerusakan;

use App\Http\Controllers\Controller;
use App\Models\LaporanKerusakan;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ListLaporanKerusakanController extends Controller
{
    public function index()
    {
        return view('pages.listlaporankerusakan.index');
    }

    public function data(Request $request)
    {
        $query = LaporanKerusakan::query();
        $query->orderByDesc('created_at');
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $start = Carbon::createFromDate($request->tahun, $request->bulan, 1)->startOfDay();
            $end = Carbon::createFromDate($request->tahun, $request->bulan, 1)->endOfMonth()->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->translatedFormat('d F Y');
            })
            ->addColumn('status_ttd', function ($row) {
                $status = [];
                if (!$row->ttd_dilaporkan_oleh) $status[] = 'Dilaporkan';
                if (!$row->ttd_diketahui_oleh) $status[] = 'Diketahui';
                if (!$row->ttd_diterima_oleh) $status[] = 'Diterima';

                return count($status) === 0
                    ? '<span class="badge bg-success">✅ Lengkap</span>'
                    : '<span class="badge bg-warning text-dark">Belum: ' . implode(', ', $status) . '</span>';
            })
            ->addColumn('aksi', function ($row) {
                return '<a href="javascript:void(0);" class="btn btn-sm btn-primary lengkapi-ttd-btn"
                    data-id="' . $row->id . '"
                    data-created_at="' . \Carbon\Carbon::parse($row->created_at)->translatedFormat('d F Y') . '"
                    data-nama="' . e($row->nama) . '"
                    data-jenis_barang="' . e($row->jenis_barang) . '"
                    data-lokasi="' . e($row->lokasi) . '"
                    data-dept_divisi="' . e($row->dept_divisi) . '"
                    data-keterangan="' . e($row->keterangan) . '"
                    data-uraian="' . e($row->uraian_masalah) . '"
                    data-nama_dilaporkan_oleh="' . e($row->nama_dilaporkan_oleh) . '"
                    data-ttd_dilaporkan_oleh="' . e($row->ttd_dilaporkan_oleh) . '"
                    data-nama_diketahui_oleh="' . e($row->nama_diketahui_oleh) . '"
                    data-ttd_diketahui_oleh="' . e($row->ttd_diketahui_oleh) . '"
                    data-nama_diterima_oleh="' . e($row->nama_diterima_oleh) . '"
                    data-ttd_diterima_oleh="' . e($row->ttd_diterima_oleh) . '">
                    ✏️ Lengkapi TTD</a>';
            })
            ->rawColumns(['status_ttd', 'aksi'])
            ->make(true);
    }

    public function updateTtd(Request $request, $id)
    {
        $laporan = LaporanKerusakan::findOrFail($id);

        $fields = ['dilaporkan', 'diketahui', 'diterima'];
        $rules = [];

        foreach ($fields as $field) {
            $ttdKey = "ttd_{$field}_oleh";
            $namaKey = "nama_{$field}_oleh";

            if ($request->filled($ttdKey)) {
                $rules[$namaKey] = ['required', 'string', 'max:100'];
            }
        }

        try {
            $validated = $request->validate(
                $rules,
                [
                    'required' => ':attribute wajib diisi.',
                    'max' => ':attribute maksimal :max karakter.',
                ],
                [
                    'nama_dilaporkan_oleh' => 'Nama yang melaporkan',
                    'nama_diketahui_oleh'  => 'Nama yang mengetahui',
                    'nama_diterima_oleh'   => 'Nama yang menerima',
                ]
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'errors' => $e->errors(),
                ], 422);
            }

            throw $e;
        }

        foreach ($fields as $field) {
            $ttdKey = "ttd_{$field}_oleh";
            $namaKey = "nama_{$field}_oleh";

            if ($request->filled($ttdKey)) {
                $laporan->$ttdKey = $this->saveTtd($request->$ttdKey, $id, $field);
                $laporan->$namaKey = $request->$namaKey;
            }
        }

        $laporan->save();

        return response()->json(['message' => 'TTD berhasil disimpan']);
    }



    protected function saveTtd($base64Image, $id, $role)
    {
        $filename = 'laporan_' . uniqid() . '.png';
        $folder = "storage/ttd_laporan_kerusakan/ttd_{$role}";
        $directory = public_path($folder);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $base64Image);
        $imageData = base64_decode($base64);
        file_put_contents("{$directory}/{$filename}", $imageData);

        return "{$folder}/{$filename}";
    }

}
