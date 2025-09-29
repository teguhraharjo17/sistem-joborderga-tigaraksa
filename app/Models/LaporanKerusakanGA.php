<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKerusakanGA extends Model
{
    use HasFactory;

    protected $table = 'laporan_kerusakan_ga';

    protected $fillable = [
        'laporan_kerusakan_id',
        'rencana_tindakan_perbaikan',
        'internal_external',
        'pic',
        'tindakan_perbaikan',
        'nama_dikerjakan_oleh',
        'ttd_dikerjakan_oleh',
        'nama_dikontrol_oleh',
        'ttd_dikontrol_oleh',
        'bukti_upload',
        'selesai_perbaikan',
        'status_perbaikan',
    ];

    public function laporanKerusakan()
    {
        return $this->belongsTo(LaporanKerusakan::class, 'laporan_kerusakan_id');
    }
}
