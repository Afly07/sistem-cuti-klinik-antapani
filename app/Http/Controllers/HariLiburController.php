<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use Illuminate\Http\Request;

class HariLiburController extends Controller
{
    public function index()
    {
        $hariLibur = HariLibur::orderBy('tanggal')->get();
        return view('hari_libur.index', compact('hariLibur'));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $data = $request->validate([
            'tanggal' => 'required|date|unique:hari_libur,tanggal',
            'nama'    => 'required|string|max:100',
            'jenis'   => 'required|in:nasional,perusahaan',
        ]);

        $data['aktif'] = true;
        HariLibur::create($data);

        return back()->with('success', 'Data hari libur berhasil ditambahkan.');
    }

    public function toggleAktif(Request $request, HariLibur $hari_libur)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $hari_libur->update(['aktif' => !$hari_libur->aktif]);

        return back()->with('success', 'Status hari libur berhasil diperbarui.');
    }

    public function destroy(Request $request, HariLibur $hari_libur)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);
        $hari_libur->delete();

        return back()->with('success', 'Data hari libur berhasil dihapus.');
    }
}
