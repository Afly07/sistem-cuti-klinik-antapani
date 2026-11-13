<?php

namespace App\Http\Controllers;

use App\Models\BagianOrganisasi;
use Illuminate\Http\Request;

class BagianOrganisasiController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $bagianOrganisasi = BagianOrganisasi::withCount('pegawai')->orderBy('kode')->get();

        return view('master.bagian_organisasi', compact('bagianOrganisasi'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $data = $request->validate([
            'kode' => 'required|string|max:20|unique:bagian_organisasi,kode',
            'nama' => 'required|string|max:100',
        ]);

        $data['kode']  = strtoupper($data['kode']);
        $data['aktif'] = true;

        BagianOrganisasi::create($data);

        return back()->with('success', 'Bagian organisasi baru berhasil ditambahkan.');
    }

    public function update(Request $request, BagianOrganisasi $bagianOrganisasi)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $data = $request->validate([
            'kode'  => 'required|string|max:20|unique:bagian_organisasi,kode,' . $bagianOrganisasi->id,
            'nama'  => 'required|string|max:100',
            'aktif' => 'nullable|boolean',
        ]);

        $bagianOrganisasi->update([
            'kode'  => strtoupper($data['kode']),
            'nama'  => $data['nama'],
            'aktif' => $request->has('aktif') ? (bool) $request->aktif : $bagianOrganisasi->aktif,
        ]);

        return back()->with('success', 'Data bagian organisasi berhasil diperbarui.');
    }

    public function toggleAktif(Request $request, BagianOrganisasi $bagianOrganisasi)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $bagianOrganisasi->update(['aktif' => !$bagianOrganisasi->aktif]);

        return back()->with('success', 'Status aktif bagian organisasi berhasil diperbarui.');
    }
}
