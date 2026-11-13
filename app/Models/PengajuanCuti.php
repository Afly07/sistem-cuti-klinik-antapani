<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanCuti extends Model
{
    protected $table = 'pengajuan_cuti';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function atasanPenyetuju()
    {
        return $this->belongsTo(Pegawai::class, 'atasan_penyetuju_id');
    }

    public function persetujuan()
    {
        return $this->hasMany(PersetujuanCuti::class, 'pengajuan_cuti_id')->orderBy('diputuskan_pada');
    }

    public function labelStatus(): string
    {
        return match ($this->status) {
            'draf'            => 'Draf',
            'menunggu_atasan' => 'Menunggu Atasan',
            'menunggu_hr'     => 'Menunggu Admin HR',
            'disetujui'       => 'Disetujui',
            'ditolak'         => 'Ditolak',
            'dibatalkan'      => 'Dibatalkan',
            default           => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}
