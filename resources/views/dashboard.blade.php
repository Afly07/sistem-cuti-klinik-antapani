@extends('layouts.app')
@section('title', 'Dashboard Cuti')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
            {{ auth()->user()->pegawai->nama ?? auth()->user()->nama }}
        </h1>
        <p class="text-base text-slate-600 mt-0.5">
            {{ auth()->user()->pegawai->bagianOrganisasi->nama ?? 'Klinik Utama Medika Antapani' }}
        </p>
    </div>
    <a href="{{ route('pengajuan.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold transition">
        Ajukan Cuti Baru
    </a>
</div>

<!-- Panel Saldo Menyatu -->
<div class="bg-white rounded-xl border border-slate-200 grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-slate-200 mb-10">
    <div class="p-6">
        <p class="text-sm font-medium text-slate-500">Jatah Cuti {{ date('Y') }}</p>
        <p class="text-3xl font-bold text-slate-900 tabular-nums mt-2">{{ $saldo->jatah_awal ?? 12 }} Hari</p>
    </div>
    <div class="p-6">
        <p class="text-sm font-medium text-slate-500">Terpakai</p>
        <p class="text-3xl font-bold text-orange-600 tabular-nums mt-2">{{ $saldo->terpakai ?? 0 }} Hari</p>
    </div>
    <div class="p-6">
        <p class="text-sm font-medium text-slate-500">Saldo Tersedia</p>
        <p class="text-3xl font-bold text-cyan-700 tabular-nums mt-2">{{ $saldo->saldo_tersedia ?? 12 }} Hari</p>
    </div>
</div>

<!-- Monitoring Sisa Saldo Cuti Staf (Atasan & HR) -->
@if(in_array(auth()->user()->peran->kode ?? '', ['atasan', 'admin_hr']))
<section>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-slate-900">Monitoring Sisa Saldo Cuti Staf</h2>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-5 font-semibold">NIP</th>
                        <th class="py-3.5 px-5 font-semibold">Nama Staf</th>
                        <th class="py-3.5 px-5 font-semibold">Bagian</th>
                        <th class="py-3.5 px-5 font-semibold">Terpakai</th>
                        <th class="py-3.5 px-5 font-semibold">Sisa Saldo</th>
                        <th class="py-3.5 px-5 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($stafBawahan as $staf)
                        @php
                            $s = $staf->saldoCuti->first();
                            $terpakai = $s->terpakai ?? 0;
                            $sisa = $s->saldo_tersedia ?? 12;
                        @endphp
                        <tr class="hover:bg-slate-50/70">
                            <td class="py-4 px-5 text-slate-600 tabular-nums">{{ $staf->nomor_induk }}</td>
                            <td class="py-4 px-5 font-semibold text-slate-900">{{ $staf->nama }}</td>
                            <td class="py-4 px-5 text-slate-600">{{ $staf->bagianOrganisasi->nama ?? '-' }}</td>
                            <td class="py-4 px-5 text-slate-700 tabular-nums">{{ $terpakai }} Hari</td>
                            <td class="py-4 px-5 font-bold text-cyan-700 tabular-nums">{{ $sisa }} Hari</td>
                            <td class="py-4 px-5">
                                @if($terpakai == 0)
                                    <span class="inline-block px-3 py-1 rounded-md text-sm font-medium bg-orange-50 text-orange-800">
                                        Belum Pernah Cuti
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 rounded-md text-sm font-medium bg-cyan-50 text-cyan-800">
                                        Aktif Cuti
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endif
@endsection