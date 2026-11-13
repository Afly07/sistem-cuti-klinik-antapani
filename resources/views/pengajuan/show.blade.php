@extends('layouts.app')
@section('title', 'Detail Pengajuan & Timeline Persetujuan')
@section('subtitle', 'Informasi lengkap dokumen pengajuan cuti beserta jejak audit persetujuan berjenjang')

@section('content')
@php
    $keputusanAtasan = $pengajuan->persetujuan->where('tahap', 'atasan')->first();
    $keputusanHr = $pengajuan->persetujuan->where('tahap', 'admin_hr')->first();
    $tanpaAtasan = is_null($pengajuan->atasan_penyetuju_id);
@endphp

<div class="mb-5">
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('pengajuan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 hover:text-teal-600">
        ← Kembali ke Daftar Pengajuan
    </a>
</div>

<!-- Stepper Timeline -->
<div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm mb-6">
    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Alur Pelacakan Status Pengajuan ({{ $pengajuan->nomor_pengajuan }})</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Step 1 -->
        <div class="p-4 rounded-xl border {{ $pengajuan->status === 'draf' ? 'border-indigo-300 bg-indigo-50/50' : 'border-emerald-200 bg-emerald-50/50' }}">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Tahap 1</span>
            <h4 class="text-sm font-bold text-slate-900 mt-0.5">Pengajuan Dibuat</h4>
            <p class="text-xs text-slate-600 mt-1">{{ $pengajuan->pegawai->nama ?? '-' }}</p>
            <p class="text-[11px] text-slate-400 mt-0.5">{{ $pengajuan->diajukan_pada }}</p>
        </div>

        <!-- Step 2 -->
        <div class="p-4 rounded-xl border {{ $keputusanAtasan ? ($keputusanAtasan->keputusan === 'disetujui' ? 'border-emerald-200 bg-emerald-50/50' : 'border-rose-200 bg-rose-50/50') : ($pengajuan->status === 'menunggu_atasan' ? 'border-amber-300 bg-amber-50/60' : 'border-slate-200 bg-slate-50') }}">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Tahap 2</span>
            <h4 class="text-sm font-bold text-slate-900 mt-0.5">Verifikasi Atasan Divisi</h4>
            @if($tanpaAtasan)
                <p class="text-xs text-cyan-700 font-semibold mt-1">Langsung ke Admin HR (Jabatan Atasan)</p>
            @elseif($keputusanAtasan)
                <p class="text-xs font-semibold {{ $keputusanAtasan->keputusan === 'disetujui' ? 'text-emerald-700' : 'text-rose-700' }} mt-1">
                    {{ ucfirst($keputusanAtasan->keputusan) }} oleh {{ $keputusanAtasan->pemberiKeputusan->nama ?? '-' }}
                </p>
                <p class="text-[11px] text-slate-500 mt-0.5">{{ $keputusanAtasan->diputuskan_pada }}</p>
            @else
                <p class="text-xs text-amber-700 mt-1">Menunggu: {{ $pengajuan->atasanPenyetuju->nama ?? 'Atasan' }}</p>
            @endif
        </div>

        <!-- Step 3 -->
        <div class="p-4 rounded-xl border {{ $keputusanHr ? ($keputusanHr->keputusan === 'disetujui' ? 'border-emerald-200 bg-emerald-50/50' : 'border-rose-200 bg-rose-50/50') : ($pengajuan->status === 'menunggu_hr' ? 'border-cyan-300 bg-cyan-50/60' : 'border-slate-200 bg-slate-50') }}">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Tahap 3</span>
            <h4 class="text-sm font-bold text-slate-900 mt-0.5">Keputusan Akhir Admin HR</h4>
            @if($keputusanHr)
                <p class="text-xs font-semibold {{ $keputusanHr->keputusan === 'disetujui' ? 'text-emerald-700' : 'text-rose-700' }} mt-1">
                    {{ ucfirst($keputusanHr->keputusan) }} oleh {{ $keputusanHr->pemberiKeputusan->nama ?? 'Admin HR' }}
                </p>
                <p class="text-[11px] text-slate-500 mt-0.5">{{ $keputusanHr->diputuskan_pada }}</p>
            @elseif($pengajuan->status === 'menunggu_hr')
                <p class="text-xs text-cyan-700 font-semibold mt-1">Sedang ditinjau Admin HR</p>
            @else
                <p class="text-xs text-slate-400 mt-1">Menunggu tahap sebelumnya</p>
            @endif
        </div>

        <!-- Step 4 -->
        <div class="p-4 rounded-xl border {{ $pengajuan->status === 'disetujui' ? 'border-teal-300 bg-teal-50/60' : ($pengajuan->status === 'ditolak' || $pengajuan->status === 'dibatalkan' ? 'border-rose-200 bg-rose-50/40' : 'border-slate-200 bg-slate-50') }}">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Tahap Akhir</span>
            <h4 class="text-sm font-bold text-slate-900 mt-0.5">Pembaruan Saldo Cuti</h4>
            @if($pengajuan->status === 'disetujui')
                <p class="text-xs font-bold text-teal-700 mt-1">Disetujui • Saldo Dipotong 1 Hari</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Sisa Saldo: {{ $saldoPegawai->saldo_tersedia ?? '-' }} Hari</p>
            @elseif($pengajuan->status === 'ditolak')
                <p class="text-xs font-bold text-rose-700 mt-1">Pengajuan Ditolak (Saldo Utuh)</p>
            @elseif($pengajuan->status === 'dibatalkan')
                <p class="text-xs font-bold text-slate-600 mt-1">Dibatalkan pada {{ $pengajuan->dibatalkan_pada }}</p>
            @else
                <p class="text-xs text-slate-400 mt-1">Saldo belum dipotong</p>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Detail Card -->
    <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
            <h3 class="text-sm font-bold">Rincian Dokumen Pengajuan Cuti</h3>
            <span class="px-3 py-1 rounded-full bg-white/10 text-xs font-mono font-bold">{{ $pengajuan->nomor_pengajuan }}</span>
        </div>
        <div class="p-6 space-y-4 text-sm">
            <div class="grid grid-cols-2 gap-4 pb-4 border-b border-slate-100">
                <div>
                    <span class="text-xs text-slate-400 block">Nama Pegawai</span>
                    <span class="font-bold text-slate-900">{{ $pengajuan->pegawai->nama ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Nomor Induk Pegawai (NIP)</span>
                    <span class="font-mono font-bold text-slate-800">{{ $pengajuan->pegawai->nomor_induk ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Bagian Organisasi / Instalasi</span>
                    <span class="font-semibold text-slate-800">{{ $pengajuan->pegawai->bagianOrganisasi->nama ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Tanggal Cuti Diajukan</span>
                    <span class="font-bold text-teal-700">{{ $pengajuan->tanggal_cuti }} (1 Hari Kerja)</span>
                </div>
            </div>

            <div>
                <span class="text-xs text-slate-400 block mb-1">Alasan Pengajuan Cuti</span>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 text-slate-800 text-xs leading-relaxed">
                    {{ $pengajuan->alasan }}
                </div>
            </div>

            @can('setujuiAtasan', $pengajuan)
            <div class="pt-4 border-t border-slate-200">
                <h4 class="text-xs font-bold uppercase tracking-wider text-cyan-700 mb-2">Form Keputusan Tahap Atasan</h4>
                <form action="{{ route('pengajuan.keputusanAtasan', $pengajuan->id) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <input type="text" name="catatan" placeholder="Tambahkan catatan verifikasi atasan..." class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs">
                    <div class="flex gap-2">
                        <button name="keputusan" value="disetujui" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold">Setujui & Teruskan ke HR</button>
                        <button name="keputusan" value="ditolak" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold">Tolak Pengajuan</button>
                    </div>
                </form>
            </div>
            @endcan

            @can('setujuiHr', $pengajuan)
            <div class="pt-4 border-t border-slate-200">
                <h4 class="text-xs font-bold uppercase tracking-wider text-amber-700 mb-2">Form Keputusan Akhir Admin HR</h4>
                <form action="{{ route('pengajuan.keputusanHr', $pengajuan->id) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <input type="text" name="catatan" placeholder="Tambahkan catatan keputusan akhir HR..." class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-xs">
                    <div class="flex gap-2">
                        <button name="keputusan" value="disetujui" class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold">Setujui Akhir & Potong Saldo</button>
                        <button name="keputusan" value="ditolak" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold">Tolak Pengajuan</button>
                    </div>
                </form>
            </div>
            @endcan
        </div>
    </div>

    <!-- Log Persetujuan -->
    <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="text-sm font-bold text-slate-900">Log Riwayat Persetujuan (`persetujuan_cuti`)</h3>
        </div>
        <div class="p-5 space-y-3">
            @forelse($pengajuan->persetujuan as $log)
                <div class="p-4 rounded-xl border {{ $log->keputusan === 'disetujui' ? 'border-emerald-200 bg-emerald-50/40' : 'border-rose-200 bg-rose-50/40' }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider {{ $log->keputusan === 'disetujui' ? 'text-emerald-800' : 'text-rose-800' }}">
                            Tahap {{ $log->tahap === 'atasan' ? 'Atasan Divisi' : 'Admin HR' }} — {{ strtoupper($log->keputusan) }}
                        </span>
                        <span class="text-[11px] text-slate-400">{{ $log->diputuskan_pada }}</span>
                    </div>
                    <p class="text-xs font-semibold text-slate-800 mt-1">Oleh: {{ $log->pemberiKeputusan->nama ?? '-' }}</p>
                    <p class="text-xs text-slate-600 mt-1 italic">"{{ $log->catatan ?: 'Tanpa catatan tambahan.' }}"</p>
                </div>
            @empty
                <p class="text-xs text-slate-400 text-center py-8">Belum ada catatan keputusan pada pengajuan ini.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
