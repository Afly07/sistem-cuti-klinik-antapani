@extends('layouts.app')
@section('title', 'Laporan Cuti')

@section('content')
@php
    $daftarCuti = $dataCuti ?? $laporan ?? collect();
    $daftarBagian = $daftarBagian ?? $bagianOrganisasi ?? collect();

    $statTotal = $statistik['total'] ?? $daftarCuti->count();
    $statDisetujui = $statistik['disetujui'] ?? $daftarCuti->where('status', 'disetujui')->count();
    $statMenunggu = $statistik['menunggu'] ?? $daftarCuti->whereIn('status', ['menunggu_atasan', 'menunggu_hr'])->count();
    $statDitolakBatal = $statistik['ditolak_batal'] ?? $daftarCuti->whereIn('status', ['ditolak', 'dibatalkan'])->count();
@endphp

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Laporan & Rekapitulasi Cuti</h1>
    <div class="no-print flex items-center gap-3">
        <button type="button" onclick="window.print()" class="px-4 py-2.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-800 text-sm font-semibold">
            Cetak
        </button>
        <a href="{{ route('pengajuan.eksporCsv', request()->query()) }}" class="px-5 py-2.5 rounded-lg bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold transition">
            Unduh CSV
        </a>
    </div>
</div>

<!-- Panel Statistik Menyatu -->
<div class="bg-white rounded-xl border border-slate-200 grid grid-cols-2 sm:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-slate-200 mb-8">
    <div class="p-5">
        <p class="text-sm font-medium text-slate-500">Total Pengajuan</p>
        <p class="text-2xl font-bold text-slate-900 tabular-nums mt-1">{{ $statTotal }}</p>
    </div>
    <div class="p-5">
        <p class="text-sm font-medium text-slate-500">Disetujui</p>
        <p class="text-2xl font-bold text-cyan-700 tabular-nums mt-1">{{ $statDisetujui }}</p>
    </div>
    <div class="p-5">
        <p class="text-sm font-medium text-slate-500">Menunggu</p>
        <p class="text-2xl font-bold text-orange-600 tabular-nums mt-1">{{ $statMenunggu }}</p>
    </div>
    <div class="p-5">
        <p class="text-sm font-medium text-slate-500">Ditolak / Batal</p>
        <p class="text-2xl font-bold text-slate-700 tabular-nums mt-1">{{ $statDitolakBatal }}</p>
    </div>
</div>

<!-- Filter Bar -->
<form action="{{ route('laporan.index') }}" method="GET" class="no-print bg-white rounded-xl border border-slate-200 p-5 mb-8 grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
    <div>
        <label class="block text-sm font-semibold text-slate-800 mb-1.5">Status</label>
        <select name="status" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-cyan-600">
            <option value="semua">Semua Status</option>
            @foreach(['draf', 'menunggu_atasan', 'menunggu_hr', 'disetujui', 'ditolak', 'dibatalkan'] as $st)
                <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $st)) }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-800 mb-1.5">Bagian</label>
        <select name="bagian_organisasi_id" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-cyan-600">
            <option value="">Semua Bagian</option>
            @foreach($daftarBagian as $b)
                <option value="{{ $b->id }}" {{ (string) request('bagian_organisasi_id') === (string) $b->id ? 'selected' : '' }}>
                    {{ $b->nama }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-800 mb-1.5">Tahun</label>
        <input type="number" name="tahun" value="{{ request('tahun', date('Y')) }}" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm tabular-nums outline-none focus:border-cyan-600">
    </div>
    <div class="flex gap-2">
        <button type="submit" class="px-5 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold">Terapkan</button>
        <a href="{{ route('laporan.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-medium">Reset</a>
    </div>
</form>

<!-- Tabel Laporan -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                <tr>
                    <th class="py-3.5 px-5 font-semibold">Nomor</th>
                    <th class="py-3.5 px-5 font-semibold">NIP</th>
                    <th class="py-3.5 px-5 font-semibold">Nama Pegawai</th>
                    <th class="py-3.5 px-5 font-semibold">Bagian</th>
                    <th class="py-3.5 px-5 font-semibold">Tanggal Cuti</th>
                    <th class="py-3.5 px-5 font-semibold">Status</th>
                    <th class="py-3.5 px-5 font-semibold text-right no-print">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($daftarCuti as $row)
                <tr class="hover:bg-slate-50/70">
                    <td class="py-4 px-5 font-semibold tabular-nums">
                        <a href="{{ route('pengajuan.show', $row->id) }}" class="text-cyan-700 hover:underline">{{ $row->nomor_pengajuan }}</a>
                    </td>
                    <td class="py-4 px-5 text-slate-600 tabular-nums">{{ $row->pegawai->nomor_induk ?? '-' }}</td>
                    <td class="py-4 px-5 font-semibold text-slate-900">{{ $row->pegawai->nama ?? '-' }}</td>
                    <td class="py-4 px-5 text-slate-600">{{ $row->pegawai->bagianOrganisasi->nama ?? '-' }}</td>
                    <td class="py-4 px-5 text-slate-800 tabular-nums">{{ $row->tanggal_cuti }}</td>
                    <td class="py-4 px-5">
                        <span class="inline-block px-3 py-1 rounded-md text-sm font-medium bg-slate-100 text-slate-800 capitalize">
                            {{ str_replace('_', ' ', $row->status) }}
                        </span>
                    </td>
                    <td class="py-4 px-5 text-right whitespace-nowrap no-print">
                        @can('batalkanOlehHr', $row)
                        <form action="{{ route('pengajuan.batalkanOlehHr', $row->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Batalkan cuti {{ $row->nomor_pengajuan }} dan kembalikan 1 hari saldo?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-red-600 hover:underline font-semibold">Batalkan & Refund</button>
                        </form>
                        @else
                            <a href="{{ route('pengajuan.show', $row->id) }}" class="text-cyan-700 hover:underline font-medium">Detail</a>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-10 text-center text-base text-slate-400">Tidak ada data pengajuan cuti.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection