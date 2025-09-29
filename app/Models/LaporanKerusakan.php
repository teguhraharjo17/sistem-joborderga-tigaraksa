<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKerusakan extends Model
{
    use HasFactory;

    protected $table = 'laporan_kerusakan';

    protected $fillable = [
        'jenis_barang',
        'nama',
        'dept_divisi',
        'lokasi',
        'keterangan',
        'uraian_masalah',
        'nama_dilaporkan_oleh',
        'ttd_dilaporkan_oleh',
        'nama_diketahui_oleh',
        'ttd_diketahui_oleh',
        'nama_diterima_oleh',
        'ttd_diterima_oleh',
    ];

    public function laporanGA()
    {
        return $this->hasOne(LaporanKerusakanGA::class, 'laporan_kerusakan_id');
    }
}
