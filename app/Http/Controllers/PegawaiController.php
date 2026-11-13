<?php

namespace App\Http\Controllers;

use App\Models\BagianOrganisasi;
use App\Models\Pegawai;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\SaldoCuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $daftarPegawai = Pegawai::with(['pengguna.peran', 'bagianOrganisasi', 'atasan', 'saldoCuti'])
            ->orderBy('nomor_induk')
            ->get();

        $daftarBagian = BagianOrganisasi::where('aktif', true)->orderBy('nama')->get();
        $daftarPeran  = Peran::where('aktif', true)->get();
        $daftarAtasan = Pegawai::whereHas('pengguna.peran', fn($q) => $q->where('kode', 'atasan'))
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();

        return view('master.pegawai', compact('daftarPegawai', 'daftarBagian', 'daftarPeran', 'daftarAtasan'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $peranKaryawanId = Peran::where('kode', 'karyawan')->value('id');

    $data = $request->validate([
        'nomor_induk'          => ['required', 'string', 'max:30', 'unique:pegawai,nomor_induk'],
        'nama'                 => ['required', 'string', 'max:100'],
        'email'                => ['required', 'email', 'max:100', 'unique:pengguna,email'],
        'password'             => ['required', 'string', 'min:6'],
        'peran_id'             => ['required', 'exists:peran,id'],
        'bagian_organisasi_id' => ['required', 'exists:bagian_organisasi,id'],
        'atasan_id'            => [
            Rule::requiredIf(fn () => (int) $request->peran_id === (int) $peranKaryawanId),
            'nullable',
            'exists:pegawai,id',
        ],
        'tanggal_masuk'        => ['required', 'date'],
        'jatah_cuti'           => ['required', 'integer', 'min:0', 'max:30'],
    ]);

        DB::transaction(function () use ($data) {
            $pengguna = Pengguna::create([
                'peran_id' => $data['peran_id'],
                'nama'     => $data['nama'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'aktif'    => true,
            ]);

            $pegawai = Pegawai::create([
                'pengguna_id'          => $pengguna->id,
                'bagian_organisasi_id' => $data['bagian_organisasi_id'],
                'atasan_id'            => $data['atasan_id'] ?? null,
                'nomor_induk'          => strtoupper($data['nomor_induk']),
                'nama'                 => $data['nama'],
                'tanggal_masuk'        => $data['tanggal_masuk'],
                'jatah_cuti'           => $data['jatah_cuti'],
                'aktif'                => true,
            ]);

            SaldoCuti::create([
                'pegawai_id'     => $pegawai->id,
                'tahun'          => (int) date('Y'),
                'jatah_awal'     => $data['jatah_cuti'],
                'terpakai'       => 0,
                'saldo_tersedia' => $data['jatah_cuti'],
                'catatan'        => 'Alokasi awal pegawai baru',
            ]);
        });

        return back()->with('success', 'Data pegawai dan akun pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $data = $request->validate([
            'nama'                 => 'required|string|max:100',
            'peran_id'             => 'required|exists:peran,id',
            'bagian_organisasi_id' => 'required|exists:bagian_organisasi,id',
            'atasan_id'            => 'nullable|exists:pegawai,id',
            'jatah_cuti'           => 'required|integer|min:0|max:30',
        ]);

        DB::transaction(function () use ($pegawai, $data) {
            $pegawai->update([
                'nama'                 => $data['nama'],
                'bagian_organisasi_id' => $data['bagian_organisasi_id'],
                'atasan_id'            => $data['atasan_id'] ?? null,
                'jatah_cuti'           => $data['jatah_cuti'],
            ]);

            if ($pegawai->pengguna) {
                $pegawai->pengguna->update([
                    'nama'     => $data['nama'],
                    'peran_id' => $data['peran_id'],
                ]);
            }
        });

        return back()->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function toggleAktif(Request $request, Pegawai $pegawai)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $statusBaru = !$pegawai->aktif;
        $pegawai->update(['aktif' => $statusBaru]);
        if ($pegawai->pengguna) {
            $pegawai->pengguna->update(['aktif' => $statusBaru]);
        }

        return back()->with('success', 'Status aktif pegawai berhasil diperbarui.');
    }
}
