@extends('layouts.app')
@section('title', 'Dashboard Cuti')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-bold text-slate-900">Ringkasan Cuti Tahun {{ date('Y') }}</h1>
        <p class="text-sm text-slate-500">{{ auth()->user()->pegawai->nama ?? auth()->user()->nama }} — {{ auth()->user()->pegawai->bagianOrganisasi->nama ?? '-' }}</p>
    </div>
    <a href="{{ route('pengajuan.create') }}" class="inline-flex justify-center px-4 py-2 rounded-lg bg-teal-700 hover:bg-teal-800 text-white text-sm font-semibold">
        + Ajukan Cuti
    </a>
</div>

<!-- 3 Kartu Saldo -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-sm text-slate-500">Jatah Tahunan</p>
        <p class="text-2xl font-bold text-slate-900 mt-1">{{ $saldo->jatah_awal ?? 12 }} Hari</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-sm text-slate-500">Sudah Terpakai</p>
        <p class="text-2xl font-bold text-amber-600 mt-1">{{ $saldo->terpakai ?? 0 }} Hari</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-sm text-slate-500">Saldo Tersedia</p>
        <p class="text-2xl font-bold text-teal-700 mt-1">{{ $saldo->saldo_tersedia ?? 12 }} Hari</p>
    </div>
</div>

<!-- Pengajuan Terbaru Saya -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-8">
    <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
        <h2 class="text-base font-semibold text-slate-900">Pengajuan Terbaru Saya</h2>
        <a href="{{ route('pengajuan.index') }}" class="text-sm text-teal-700 hover:underline font-medium">Lihat Semua</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4 font-semibold">Nomor</th>
                    <th class="py-3 px-4 font-semibold">Tanggal Cuti</th>
                    <th class="py-3 px-4 font-semibold">Alasan</th>
                    <th class="py-3 px-4 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($riwayatTerbaru ?? [] as $item)
                <tr>
                    <td class="py-3 px-4 font-medium text-slate-900">{{ $item->nomor_pengajuan }}</td>
                    <td class="py-3 px-4 text-slate-700">{{ $item->tanggal_cuti }}</td>
                    <td class="py-3 px-4 text-slate-600">{{ $item->alasan }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700 capitalize">
                            {{ str_replace('_', ' ', $item->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-6 text-center text-slate-400">Belum ada pengajuan cuti.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Monitoring Staf (Atasan & HR) -->
@if(in_array(auth()->user()->peran->kode ?? '', ['atasan', 'admin_hr']))
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-200">
        <h2 class="text-base font-semibold text-slate-900">Monitoring Sisa Saldo Cuti Staf ({{ date('Y') }})</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4 font-semibold">NIP</th>
                    <th class="py-3 px-4 font-semibold">Nama Staf</th>
                    <th class="py-3 px-4 font-semibold">Bagian</th>
                    <th class="py-3 px-4 font-semibold">Terpakai</th>
                    <th class="py-3 px-4 font-semibold">Sisa Cuti</th>
                    <th class="py-3 px-4 font-semibold">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($stafBawahan as $staf)
                    @php
                        $s = $staf->saldoCuti->first();
                        $terpakai = $s->terpakai ?? 0;
                        $sisa = $s->saldo_tersedia ?? 12;
                    @endphp
                    <tr>
                        <td class="py-3 px-4 text-slate-600">{{ $staf->nomor_induk }}</td>
                        <td class="py-3 px-4 font-medium text-slate-900">{{ $staf->nama }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ $staf->bagianOrganisasi->nama ?? '-' }}</td>
                        <td class="py-3 px-4 text-slate-700">{{ $terpakai }} Hari</td>
                        <td class="py-3 px-4 font-semibold text-teal-700">{{ $sisa }} Hari</td>
                        <td class="py-3 px-4">
                            @if($terpakai == 0)
                                <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-800">Belum Pernah Cuti</span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-800">Aktif Cuti</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection