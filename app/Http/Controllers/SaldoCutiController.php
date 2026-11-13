<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\SaldoCuti;
use Illuminate\Http\Request;

class SaldoCutiController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $tahun = (int) $request->input('tahun', date('Y'));

        $daftarSaldo = SaldoCuti::with(['pegawai.bagianOrganisasi', 'pegawai.pengguna.peran'])
            ->where('tahun', $tahun)
            ->get()
            ->sortBy(fn($s) => $s->pegawai->nomor_induk ?? '');

        return view('master.saldo_cuti', compact('daftarSaldo', 'tahun'));
    }

    public function update(Request $request, SaldoCuti $saldoCuti)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $data = $request->validate([
            'jatah_awal' => 'required|integer|min:0|max:30',
            'catatan'    => 'nullable|string|max:255',
        ]);

        $saldoTersediaBaru = max(0, $data['jatah_awal'] - $saldoCuti->terpakai);

        $saldoCuti->update([
            'jatah_awal'     => $data['jatah_awal'],
            'saldo_tersedia' => $saldoTersediaBaru,
            'catatan'        => $data['catatan'],
        ]);

        return back()->with('success', 'Saldo cuti pegawai berhasil diperbarui.');
    }

    public function inisialisasiTahun(Request $request)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $tahun = (int) $request->input('tahun', date('Y'));
        $pegawaiAktif = Pegawai::where('aktif', true)->get();
        $jumlahBaru = 0;

        foreach ($pegawaiAktif as $pegawai) {
            $created = SaldoCuti::firstOrCreate(
                ['pegawai_id' => $pegawai->id, 'tahun' => $tahun],
                [
                    'jatah_awal'     => $pegawai->jatah_cuti ?? 12,
                    'terpakai'       => 0,
                    'saldo_tersedia' => $pegawai->jatah_cuti ?? 12,
                    'catatan'        => 'Inisialisasi kuota cuti tahunan ' . $tahun,
                ]
            );
            if ($created->wasRecentlyCreated) {
                $jumlahBaru++;
            }
        }

        return back()->with('success', "Inisialisasi saldo cuti tahun {$tahun} selesai ({$jumlahBaru} data baru ditambahkan).");
    }
}
