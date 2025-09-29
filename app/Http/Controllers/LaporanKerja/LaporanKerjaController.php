<?php

namespace App\Http\Controllers\LaporanKerja;

use App\Http\Controllers\Controller;
use App\Models\LaporanKerusakan;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\LaporanKerusakanGA;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Exports\LaporanKerjaExport;

class LaporanKerjaController extends Controller
{
    public function index()
    {
        return view('pages.laporankerja.index');
    }

    public function data(Request $request)
    {
        $query = LaporanKerusakan::with('laporanGA')
            ->whereNotNull('ttd_dilaporkan_oleh')
            ->whereNotNull('ttd_diketahui_oleh')
            ->whereNotNull('ttd_diterima_oleh');

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $tanggalAwal = Carbon::createFromDate($request->tahun, $request->bulan, 1)->startOfDay();
            $tanggalAkhir = Carbon::createFromDate($request->tahun, $request->bulan, 1)->endOfMonth()->endOfDay();
            $query->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
        }

        $query->orderByDesc('created_at');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->translatedFormat('d F Y');
            })
            ->addColumn('rencana_tindakan_perbaikan', function ($row) {
                if ($row->laporanGA && $row->laporanGA->rencana_tindakan_perbaikan) {
                    return \Carbon\Carbon::parse($row->laporanGA->rencana_tindakan_perbaikan)->translatedFormat('d F Y');
                }
                return '<span class="text-muted">Belum diisi</span>';
            })
            ->addColumn('selesai_perbaikan', function ($row) {
                if ($row->laporanGA && $row->laporanGA->selesai_perbaikan) {
                    return \Carbon\Carbon::parse($row->laporanGA->selesai_perbaikan)->translatedFormat('d F Y');
                }
                return '<span class="text-muted">Belum diisi</span>';
            })
            ->addColumn('pic', function ($row) {
                return $row->laporanGA && $row->laporanGA->pic
                    ? e($row->laporanGA->pic)
                    : '<span class="text-muted">Belum diisi</span>';
            })
            ->addColumn('tindakan_perbaikan', function ($row) {
                return $row->laporanGA && $row->laporanGA->tindakan_perbaikan
                    ? e($row->laporanGA->tindakan_perbaikan)
                    : '<span class="text-muted">Belum diisi</span>';
            })
            ->addColumn('status_perbaikan', function ($row) {
                $status = optional($row->laporanGA)->status_perbaikan;

                return match ($status) {
                    'belum_mulai' => '<span class="badge bg-danger text-white">Belum Mulai</span>',
                    'progress'    => '<span class="badge bg-warning text-white">Progress</span>',
                    'selesai'     => '<span class="badge bg-success text-white">Selesai</span>',
                    default       => '<span class="badge bg-secondary text-white">-</span>',
                };
            })
            ->addColumn('status_ttd', function ($row) {
                $dikerjakan = optional($row->laporanGA)->nama_dikerjakan_oleh;
                $dikontrol = optional($row->laporanGA)->nama_dikontrol_oleh;

                $status = '';

                if ($dikerjakan) {
                    $status .= '<div class="text-success">✔ Dikerjakan: <strong>' . e($dikerjakan) . '</strong></div>';
                } else {
                    $status .= '<div class="text-danger">❌ Dikerjakan: Belum</div>';
                }

                if ($dikontrol) {
                    $status .= '<div class="text-success">✔ Dikontrol: <strong>' . e($dikontrol) . '</strong></div>';
                } else {
                    $status .= '<div class="text-danger">❌ Dikontrol: Belum</div>';
                }

                return $status;
            })
            ->addColumn('bukti_upload', function ($row) {
                if ($row->laporanGA && $row->laporanGA->bukti_upload) {
                    $url = asset('storage/' . $row->laporanGA->bukti_upload);
                    return '<a href="' . $url . '" target="_blank">
                                <img src="' . $url . '" alt="Bukti Kerja" style="max-height: 60px; border-radius: 4px;">
                            </a>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('aksi', function ($row) {
                return '
                    <button type="button" class="btn btn-sm btn-primary btn-update-laporan"
                        data-id="' . $row->id . '"
                        data-nama="' . e($row->nama) . '"
                        data-tanggal="' . e($row->created_at->format('Y-m-d')) . '"
                        data-barang="' . e($row->jenis_barang) . '"
                        data-lokasi="' . e($row->lokasi) . '"
                        data-uraian="' . e($row->uraian_masalah) . '"
                        data-rencana="' . e(optional($row->laporanGA)->rencana_tindakan_perbaikan) . '"
                        data-internal_external="' . e(optional($row->laporanGA)->internal_external) . '"
                        data-pic="' . e(optional($row->laporanGA)->pic) . '"
                        data-tindakan="' . e(optional($row->laporanGA)->tindakan_perbaikan) . '"
                        data-selesai_perbaikan="' . e(optional($row->laporanGA)->selesai_perbaikan) . '"
                        data-nama_dikerjakan="' . e(optional($row->laporanGA)->nama_dikerjakan_oleh) . '"
                        data-ttd_dikerjakan="' . e(optional($row->laporanGA)->ttd_dikerjakan_oleh) . '"
                        data-nama_dikontrol="' . e(optional($row->laporanGA)->nama_dikontrol_oleh) . '"
                        data-ttd_dikontrol="' . e(optional($row->laporanGA)->ttd_dikontrol_oleh) . '"
                        data-status_perbaikan="' . e(optional($row->laporanGA)->status_perbaikan) . '"
                    >
                        <i class="bi bi-pencil-square me-1"></i> Update
                    </button>';
            })
            ->rawColumns([
                'rencana_tindakan_perbaikan',
                'selesai_perbaikan',
                'pic',
                'tindakan_perbaikan',
                'status_perbaikan',
                'status_ttd',
                'bukti_upload',
                'aksi'
            ])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'laporan_kerusakan_id'     => 'required|exists:laporan_kerusakan,id',
            'rencana_tindakan_perbaikan' => 'required|date',
            'internal_external'        => 'required|in:internal,external',
            'pic'                      => 'required|string|max:255',
            'tindakan_perbaikan'       => 'nullable|string',
            'selesai_perbaikan'        => 'required|date',
            'status_perbaikan'         => 'required|in:belum_mulai,progress,selesai',
            'nama_dikerjakan_oleh'     => 'nullable|string|max:255',
            'ttd_dikerjakan_oleh'      => 'nullable|string',
            'nama_dikontrol_oleh'      => 'nullable|string|max:255',
            'ttd_dikontrol_oleh'       => 'nullable|string',
            'bukti_upload'             => 'nullable|image|mimes:jpg,jpeg,png|max:8192',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Simpan TTD dikerjakan
        if (Str::startsWith($data['ttd_dikerjakan_oleh'], 'data:image')) {
            $image = str_replace('data:image/png;base64,', '', $data['ttd_dikerjakan_oleh']);
            $image = str_replace(' ', '+', $image);
            $filename = 'laporan_' . uniqid() . '.png';
            $path = "public/ttd_kerja_ga/ttd_dikerjakan/{$filename}";
            Storage::put($path, base64_decode($image));
            $data['ttd_dikerjakan_oleh'] = str_replace('public/', '', $path);
        }

        // Simpan TTD dikontrol
        if (Str::startsWith($data['ttd_dikontrol_oleh'], 'data:image')) {
            $image = str_replace('data:image/png;base64,', '', $data['ttd_dikontrol_oleh']);
            $image = str_replace(' ', '+', $image);
            $filename = 'laporan_' . uniqid() . '.png';
            $path = "public/ttd_kerja_ga/ttd_dikontrol/{$filename}";
            Storage::put($path, base64_decode($image));
            $data['ttd_dikontrol_oleh'] = str_replace('public/', '', $path);
        }

        // Simpan file bukti_upload jika ada
        if ($request->hasFile('bukti_upload')) {
            $file = $request->file('bukti_upload');
            $filename = 'bukti_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/bukti_upload', $filename);
            $data['bukti_upload'] = str_replace('public/', '', $path);
        }

        LaporanKerusakanGA::updateOrCreate(
            ['laporan_kerusakan_id' => $data['laporan_kerusakan_id']],
            $data
        );

        return response()->json(['message' => 'Laporan GA berhasil disimpan.']);
    }

    public function export(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        return (new LaporanKerjaExport($bulan, $tahun))->export();
    }
}
