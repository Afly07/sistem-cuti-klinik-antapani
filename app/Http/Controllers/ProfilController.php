<?php

namespace App\Http\Controllers;

use App\Models\SaldoCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index(Request $request)
    {
        $pengguna = $request->user()->load(['peran', 'pegawai.bagianOrganisasi', 'pegawai.atasan']);
        $pegawai  = $pengguna->pegawai;
        $tahun    = (int) date('Y');

        $saldo = SaldoCuti::firstOrCreate(
            ['pegawai_id' => $pegawai->id, 'tahun' => $tahun],
            ['jatah_awal' => $pegawai->jatah_cuti ?? 12, 'terpakai' => 0, 'saldo_tersedia' => $pegawai->jatah_cuti ?? 12]
        );

        $riwayatSaldo = SaldoCuti::where('pegawai_id', $pegawai->id)
            ->orderByDesc('tahun')
            ->get();

        return view('profil.index', compact('pengguna', 'pegawai', 'saldo', 'riwayatSaldo'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_baru' => 'required|string|min:6|confirmed',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password_baru),
        ]);

        return back()->with('success', 'Kata sandi akun Anda berhasil diperbarui.');
    }
}
