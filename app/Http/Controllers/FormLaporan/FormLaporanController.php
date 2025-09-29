<?php

namespace App\Http\Controllers\FormLaporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\LaporanKerusakan;

class FormLaporanController extends Controller
{
    public function index()
    {
        return view('pages.formlaporan.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_barang'         => 'required|string',
            'nama'                 => 'required|string',
            'dept'                 => 'required|string',
            'lokasi'               => 'required|string',
            'keterangan'           => 'nullable|string',
            'uraian'               => 'required|string',
            'nama_dilaporkan_oleh' => 'nullable|string|max:255',
            'ttd_dilaporkan_oleh'  => 'nullable|string',
            'nama_diketahui_oleh'  => 'nullable|string|max:255',
            'ttd_diketahui_oleh'   => 'nullable|string',
            'nama_diterima_oleh'   => 'nullable|string|max:255',
            'ttd_diterima_oleh'    => 'nullable|string',
        ]);

        $laporan = new LaporanKerusakan();
        $laporan->jenis_barang   = $request->jenis_barang;
        $laporan->nama           = $request->nama;
        $laporan->dept_divisi    = $request->dept;
        $laporan->lokasi         = $request->lokasi;
        $laporan->keterangan     = $request->keterangan;
        $laporan->uraian_masalah = $request->uraian;

        if ($request->filled('ttd_dilaporkan_oleh')) {
            $laporan->nama_dilaporkan_oleh = $request->nama_dilaporkan_oleh;
            $laporan->ttd_dilaporkan_oleh  = $this->saveTtd($request->ttd_dilaporkan_oleh, uniqid(), 'dilaporkan');
        }

        if ($request->filled('ttd_diketahui_oleh')) {
            $laporan->nama_diketahui_oleh = $request->nama_diketahui_oleh;
            $laporan->ttd_diketahui_oleh  = $this->saveTtd($request->ttd_diketahui_oleh, uniqid(), 'diketahui');
        }

        if ($request->filled('ttd_diterima_oleh')) {
            $laporan->nama_diterima_oleh = $request->nama_diterima_oleh;
            $laporan->ttd_diterima_oleh  = $this->saveTtd($request->ttd_diterima_oleh, uniqid(), 'diterima');
        }

        $laporan->save();

        return redirect()->route('formlaporan.index')->with('success', 'Laporan berhasil dibuat');
    }

    private function saveTtd($base64, $laporanId, $role)
    {
        $imageData = str_replace('data:image/png;base64,', '', $base64);
        $imageData = str_replace(' ', '+', $imageData);
        $image = base64_decode($imageData);

        $folder = "ttd_laporan_kerusakan/ttd_{$role}";
        $filename = "laporan_{$laporanId}.png";
        $path = "public/{$folder}/{$filename}";

        Storage::put($path, $image);

        return "storage/{$folder}/{$filename}";
    }
}
