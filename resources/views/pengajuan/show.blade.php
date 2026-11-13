@extends('layouts.app')
@section('title', 'Detail Pengajuan Cuti')

@section('content')
<div class="mb-6">
    <a href="{{ route('pengajuan.index') }}" class="text-sm font-semibold text-cyan-700 hover:underline">
        ← Kembali ke Riwayat Pengajuan
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tabular-nums">{{ $pengajuan->nomor_pengajuan }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">Diajukan pada {{ $pengajuan->diajukan_pada }}</p>
        </div>
        <span class="inline-block px-3.5 py-1.5 rounded-lg text-sm font-semibold bg-slate-100 text-slate-800 capitalize">
            {{ str_replace('_', ' ', $pengajuan->status) }}
        </span>
    </div>

    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm border-b border-slate-200">
        <div>
            <p class="text-slate-500 mb-1">Nama Pegawai</p>
            <p class="text-base font-semibold text-slate-900">{{ $pengajuan->pegawai->nama ?? '-' }} ({{ $pengajuan->pegawai->nomor_induk ?? '-' }})</p>
        </div>
        <div>
            <p class="text-slate-500 mb-1">Bagian Organisasi</p>
            <p class="text-base font-semibold text-slate-900">{{ $pengajuan->pegawai->bagianOrganisasi->nama ?? '-' }}</p>
        </div>
        <div>
            <p class="text-slate-500 mb-1">Tanggal Cuti</p>
            <p class="text-base font-bold text-cyan-700 tabular-nums">{{ $pengajuan->tanggal_cuti }}</p>
        </div>
        <div>
            <p class="text-slate-500 mb-1">Atasan Penyetuju</p>
            <p class="text-base font-semibold text-slate-900">{{ $pengajuan->atasanPenyetuju->nama ?? 'Langsung ke Admin HR' }}</p>
        </div>
        <div class="sm:col-span-2">
            <p class="text-slate-500 mb-1">Alasan Cuti</p>
            <p class="text-base text-slate-800">{{ $pengajuan->alasan }}</p>
        </div>
    </div>

    <!-- Form Keputusan Atasan -->
    @can('setujuiAtasan', $pengajuan)
    <div class="p-6 bg-slate-50 border-b border-slate-200">
        <h2 class="text-base font-bold text-slate-900 mb-3">Keputusan Tahap Atasan</h2>
        <form action="{{ route('pengajuan.keputusanAtasan', $pengajuan->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
            @csrf @method('PATCH')
            <input type="text" name="catatan" placeholder="Catatan verifikasi atasan..." class="flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-cyan-600">
            <button name="keputusan" value="disetujui" class="px-5 py-2.5 rounded-lg bg-cyan-700 hover:bg-cyan-800 text-white text-sm font-semibold">Setujui</button>
            <button name="keputusan" value="ditolak" class="px-5 py-2.5 rounded-lg border border-red-300 text-red-700 hover:bg-red-50 text-sm font-semibold">Tolak</button>
        </form>
    </div>
    @endcan

    <!-- Form Keputusan Akhir Admin HR -->
    @can('setujuiHr', $pengajuan)
    <div class="p-6 bg-slate-50 border-b border-slate-200">
        <h2 class="text-base font-bold text-slate-900 mb-3">Keputusan Akhir Admin HR</h2>
        <form action="{{ route('pengajuan.keputusanHr', $pengajuan->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
            @csrf @method('PATCH')
            <input type="text" name="catatan" placeholder="Catatan keputusan akhir HR..." class="flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-cyan-600">
            <button name="keputusan" value="disetujui" class="px-5 py-2.5 rounded-lg bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold">Setujui & Potong Saldo</button>
            <button name="keputusan" value="ditolak" class="px-5 py-2.5 rounded-lg border border-red-300 text-red-700 hover:bg-red-50 text-sm font-semibold">Tolak</button>
        </form>
    </div>
    @endcan

    <!-- Tombol Pembatalan Darurat & Refund Saldo oleh Admin HR -->
    @can('batalkanOlehHr', $pengajuan)
    <div class="p-6 bg-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <p class="text-sm text-slate-700">Batalkan cuti yang telah disetujui ini dan kembalikan 1 hari ke saldo tersedia pegawai.</p>
        <form action="{{ route('pengajuan.batalkanOlehHr', $pengajuan->id) }}" method="POST"
              onsubmit="return confirm('Batalkan cuti ini dan kembalikan 1 hari saldo pegawai?')">
            @csrf @method('PATCH')
            <button type="submit" class="px-4 py-2.5 rounded-lg border border-red-300 text-red-700 hover:bg-red-50 text-sm font-semibold">
                Batalkan & Kembalikan Saldo
            </button>
        </form>
    </div>
    @endcan
</div>

<!-- Riwayat Persetujuan -->
<section>
    <h2 class="text-lg font-bold text-slate-900 mb-4">Riwayat Keputusan</h2>
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                <tr>
                    <th class="py-3.5 px-5 font-semibold">Tahap</th>
                    <th class="py-3.5 px-5 font-semibold">Pemberi Keputusan</th>
                    <th class="py-3.5 px-5 font-semibold">Keputusan</th>
                    <th class="py-3.5 px-5 font-semibold">Catatan</th>
                    <th class="py-3.5 px-5 font-semibold">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($pengajuan->persetujuan as $log)
                <tr>
                    <td class="py-4 px-5 font-medium text-slate-800 capitalize">{{ str_replace('_', ' ', $log->tahap) }}</td>
                    <td class="py-4 px-5 font-semibold text-slate-900">{{ $log->pemberiKeputusan->nama ?? '-' }}</td>
                    <td class="py-4 px-5">
                        <span class="inline-block px-3 py-1 rounded-md text-sm font-medium {{ $log->keputusan === 'disetujui' ? 'bg-cyan-50 text-cyan-800' : 'bg-red-50 text-red-800' }} capitalize">
                            {{ $log->keputusan }}
                        </span>
                    </td>
                    <td class="py-4 px-5 text-slate-600">{{ $log->catatan ?: '-' }}</td>
                    <td class="py-4 px-5 text-slate-600 tabular-nums">{{ $log->diputuskan_pada }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-400">Belum ada catatan keputusan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection