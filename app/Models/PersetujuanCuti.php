<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersetujuanCuti extends Model
{
    protected $table = 'persetujuan_cuti';
    public $timestamps = false;
    protected $guarded = ['id'];

    public function pengajuanCuti()
    {
        return $this->belongsTo(PengajuanCuti::class, 'pengajuan_cuti_id');
    }

    public function pemberiKeputusan()
    {
        return $this->belongsTo(Pegawai::class, 'pemberi_keputusan_id');
    }
}
