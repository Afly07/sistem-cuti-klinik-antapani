@extends('layouts.app')
@section('title', 'Laporan & Rekapitulasi Cuti')

@section('content')
@php
    $daftarCuti = $dataCuti ?? $laporan ?? collect();
    $daftarBagian = $daftarBagian ?? $bagianOrganisasi ?? collect();

    $statTotal = $statistik['total'] ?? $daftarCuti->count();
    $statDisetujui = $statistik['disetujui'] ?? $daftarCuti->where('status', 'disetujui')->count();
    $statMenunggu = $statistik['menunggu'] ?? $daftarCuti->whereIn('status', ['menunggu_atasan', 'menunggu_hr'])->count();
    $statDitolakBatal = $statistik['ditolak_batal'] ?? $daftarCuti->whereIn('status', ['ditolak', 'dibatalkan'])->count();
@endphp

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <h1 class="text-xl font-bold text-slate-900">Laporan & Rekapitulasi Cuti</h1>
    <div class="flex items-center gap-2">
        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm font-medium">
            Cetak
        </button>
        <a href="{{ route('pengajuan.eksporCsv', request()->query()) }}" class="px-4 py-2 rounded-lg bg-teal-700 hover:bg-teal-800 text-white text-sm font-semibold">
            Ekspor CSV
        </a>
    </div>
</div>

<!-- Ringkasan Statistik -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <p class="text-sm text-slate-500">Total Pengajuan</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $statTotal }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <p class="text-sm text-slate-500">Disetujui</p>
        <p class="text-2xl font-bold text-teal-700 mt-1">{{ $statDisetujui }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <p class="text-sm text-slate-500">Menunggu</p>
        <p class="text-2xl font-bold text-amber-600 mt-1">{{ $statMenunggu }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <p class="text-sm text-slate-500">Ditolak / Batal</p>
        <p class="text-2xl font-bold text-rose-600 mt-1">{{ $statDitolakBatal }}</p>
    </div>
</div>

<!-- Filter -->
<form action="{{ route('laporan.index') }}" method="GET" class="bg-white rounded-xl border border-slate-200 p-4 mb-6 grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
        <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            <option value="semua">Semua Status</option>
            @foreach(['draf', 'menunggu_atasan', 'menunggu_hr', 'disetujui', 'ditolak', 'dibatalkan'] as $st)
                <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $st)) }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Bagian</label>
        <select name="bagian_organisasi_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
            <option value="">Semua Bagian</option>
            @foreach($daftarBagian as $b)
                <option value="{{ $b->id }}" {{ (string) request('bagian_organisasi_id') === (string) $b->id ? 'selected' : '' }}>
                    {{ $b->nama }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Tahun</label>
        <input type="number" name="tahun" value="{{ request('tahun', date('Y')) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
    </div>
    <div class="flex gap-2">
        <button type="submit" class="px-4 py-2 rounded-lg bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium">Filter</button>
        <a href="{{ route('laporan.index') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 text-sm font-medium">Reset</a>
    </div>
</form>

<!-- Tabel Data Laporan -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4 font-semibold">Nomor</th>
                    <th class="py-3 px-4 font-semibold">NIP</th>
                    <th class="py-3 px-4 font-semibold">Nama Pegawai</th>
                    <th class="py-3 px-4 font-semibold">Bagian</th>
                    <th class="py-3 px-4 font-semibold">Tanggal Cuti</th>
                    <th class="py-3 px-4 font-semibold">Alasan</th>
                    <th class="py-3 px-4 font-semibold">Status</th>
                    <th class="py-3 px-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($daftarCuti as $row)
                <tr>
                    <td class="py-3 px-4 font-medium text-slate-900">
                        <a href="{{ route('pengajuan.show', $row->id) }}" class="text-teal-700 hover:underline">{{ $row->nomor_pengajuan }}</a>
                    </td>
                    <td class="py-3 px-4 text-slate-600">{{ $row->pegawai->nomor_induk ?? '-' }}</td>
                    <td class="py-3 px-4 font-medium text-slate-900">{{ $row->pegawai->nama ?? '-' }}</td>
                    <td class="py-3 px-4 text-slate-600">{{ $row->pegawai->bagianOrganisasi->nama ?? '-' }}</td>
                    <td class="py-3 px-4 text-slate-700">{{ $row->tanggal_cuti }}</td>
                    <td class="py-3 px-4 text-slate-600">{{ $row->alasan }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700 capitalize">
                            {{ str_replace('_', ' ', $row->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right whitespace-nowrap">
                        @can('batalkanOlehHr', $row)
                        <form action="{{ route('pengajuan.batalkanOlehHr', $row->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Batalkan cuti {{ $row->nomor_pengajuan }} dan kembalikan 1 hari saldo?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-rose-600 hover:underline text-sm font-medium">
                                Batalkan & Refund
                            </button>
                        </form>
                        @else
                            <a href="{{ route('pengajuan.show', $row->id) }}" class="text-teal-700 hover:underline text-sm font-medium">
                                Detail
                            </a>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="py-8 text-center text-slate-400">Tidak ada data pengajuan cuti.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection