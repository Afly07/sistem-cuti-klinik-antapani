<?php

namespace App\Policies;

use App\Models\Pengguna;
use App\Models\PengajuanCuti;
use Carbon\Carbon;

class PengajuanCutiPolicy
{
    public function batalkan(Pengguna $pengguna, PengajuanCuti $pengajuan): bool
    {
        return $pengguna->pegawai
            && $pengguna->pegawai->id === $pengajuan->pegawai_id
            && in_array($pengajuan->status, ['draf', 'menunggu_atasan', 'menunggu_hr'], true);
    }

    public function ajukanDraf(Pengguna $pengguna, PengajuanCuti $pengajuan): bool
    {
        return $pengguna->pegawai
            && $pengguna->pegawai->id === $pengajuan->pegawai_id
            && $pengajuan->status === 'draf';
    }

    public function setujuiAtasan(Pengguna $pengguna, PengajuanCuti $pengajuan): bool
    {
        return $pengguna->peran->kode === 'atasan'
            && $pengajuan->status === 'menunggu_atasan'
            && $pengguna->pegawai
            && $pengajuan->atasan_penyetuju_id === $pengguna->pegawai->id;
    }

    public function setujuiHr(Pengguna $pengguna, PengajuanCuti $pengajuan): bool
    {
        return $pengguna->peran->kode === 'admin_hr'
            && $pengajuan->status === 'menunggu_hr';
    }

    public function batalkanOlehHr(Pengguna $pengguna, PengajuanCuti $pengajuan): bool
    {
        return $pengguna->peran->kode === 'admin_hr'
            && $pengajuan->status === 'disetujui'
            && Carbon::parse($pengajuan->tanggal_cuti)->greaterThanOrEqualTo(Carbon::today());
    }
}