@extends('layouts.app')
@section('title', 'Dashboard & Ringkasan Saldo Cuti')
@section('subtitle', 'Pantau kuota cuti tahunan, status pengajuan aktif, dan saldo staf dalam satu layar terpadu')

@section('content')
@php
    $pengguna = auth()->user();
    $pegawai = $pengguna->pegawai;
    $peranKode = $pengguna->peran->kode ?? 'karyawan';
    $jatahAwal = $saldo->jatah_awal ?? 12;
    $terpakai = $saldo->terpakai ?? 0;
    $tersedia = $saldo->saldo_tersedia ?? 12;
    $persenTerpakai = $jatahAwal > 0 ? min(100, round(($terpakai / $jatahAwal) * 100)) : 0;
@endphp

<!-- Welcome Banner -->
<div class="mb-6 rounded-2xl bg-gradient-to-r from-navy-900 via-slate-900 to-teal-900 text-white p-6 shadow-md flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-500/20 border border-teal-400/30 text-teal-300 text-xs font-semibold mb-2">
            <span>Periode Cuti Tahunan {{ date('Y') }}</span>
            <span>•</span>
            <span>{{ $pegawai->bagianOrganisasi->nama ?? 'Klinik Utama Medika Antapani' }}</span>
        </div>
        <h3 class="text-xl font-extrabold tracking-tight">Selamat Bertugas, {{ $pegawai->nama ?? $pengguna->nama }}</h3>
        <p class="text-xs text-slate-300 mt-1">
            NIP: <strong>{{ $pegawai->nomor_induk ?? '-' }}</strong>
            @if($pegawai && $pegawai->atasan)
                • Atasan Langsung: <strong>{{ $pegawai->atasan->nama }}</strong>
            @else
                • Persetujuan Cuti Langsung ke <strong>Admin HR</strong>
            @endif
        </p>
    </div>
    <div class="flex flex-wrap items-center gap-2.5">
        <a href="{{ route('pengajuan.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold text-xs shadow-lg shadow-teal-500/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            <span>Ajukan Cuti Baru</span>
        </a>
        <a href="{{ route('pengajuan.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs border border-white/15 transition">
            <span>Riwayat Pengajuan</span>
        </a>
    </div>
</div>

<!-- Role Action Alerts -->
@if($peranKode === 'atasan' && ($antreanAtasanCount ?? 0) > 0)
<div class="mb-6 rounded-2xl bg-cyan-50 border border-cyan-200 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-cyan-600 text-white flex items-center justify-center font-bold text-sm flex-shrink-0">{{ $antreanAtasanCount }}</div>
        <div>
            <h4 class="text-sm font-bold text-cyan-950">Menunggu Verifikasi Tahap Atasan</h4>
            <p class="text-xs text-cyan-800">Terdapat {{ $antreanAtasanCount }} pengajuan cuti staf bawahan Anda yang memerlukan keputusan.</p>
        </div>
    </div>
    <a href="{{ route('persetujuan.atasan') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold transition">
        Tinjau Pengajuan Bawahan →
    </a>
</div>
@endif

@if($peranKode === 'admin_hr' && ($antreanHrCount ?? 0) > 0)
<div class="mb-6 rounded-2xl bg-amber-50 border border-amber-200 p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-500 text-slate-950 flex items-center justify-center font-bold text-sm flex-shrink-0">{{ $antreanHrCount }}</div>
        <div>
            <h4 class="text-sm font-bold text-amber-950">Antrean Persetujuan Akhir Admin HR</h4>
            <p class="text-xs text-amber-800">Terdapat {{ $antreanHrCount }} pengajuan cuti berstatus Menunggu HR yang siap diputuskan dan dipotong saldonya.</p>
        </div>
    </div>
    <a href="{{ route('persetujuan.hr') }}" class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold transition">
        Buka Panel Persetujuan HR →
    </a>
</div>
@endif

<!-- Primary Leave Balance Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Jatah Cuti Tahunan ({{ date('Y') }})</span>
            <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-slate-900 mt-3">{{ $jatahAwal }} Hari</p>
        <p class="text-xs text-slate-500 mt-1">Alokasi hak cuti tahunan reguler pegawai</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-amber-600">Cuti Terpakai</span>
            <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-slate-900 mt-3">{{ $terpakai }} Hari</p>
        <div class="mt-2.5 w-full bg-slate-100 h-2 rounded-full overflow-hidden">
            <div class="bg-amber-500 h-full rounded-full" style="width: {{ $persenTerpakai }}%"></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-teal-200 p-5 shadow-sm ring-1 ring-teal-500/10">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-teal-700">Saldo Tersedia</span>
            <span class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
        </div>
        <p class="text-3xl font-extrabold text-teal-600 mt-3">{{ $tersedia }} Hari</p>
        <p class="text-xs text-slate-500 mt-1">Dapat diajukan pada hari kerja aktif</p>
    </div>
</div>

@if($ringkasanKlinik)
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-slate-900 text-white rounded-2xl p-4">
        <span class="text-[11px] font-semibold text-slate-400 uppercase">Total Pegawai Aktif</span>
        <p class="text-2xl font-extrabold mt-1">{{ $ringkasanKlinik['total_pegawai'] }} Orang</p>
    </div>
    <div class="bg-slate-900 text-white rounded-2xl p-4">
        <span class="text-[11px] font-semibold text-slate-400 uppercase">Bagian Organisasi</span>
        <p class="text-2xl font-extrabold mt-1">{{ $ringkasanKlinik['total_bagian'] }} Instalasi</p>
    </div>
    <div class="bg-slate-900 text-white rounded-2xl p-4">
        <span class="text-[11px] font-semibold text-teal-400 uppercase">Total Cuti Disetujui</span>
        <p class="text-2xl font-extrabold text-teal-400 mt-1">{{ $ringkasanKlinik['cuti_disetujui'] }} Pengajuan</p>
    </div>
    <div class="bg-slate-900 text-white rounded-2xl p-4">
        <span class="text-[11px] font-semibold text-amber-400 uppercase">Menunggu Keputusan</span>
        <p class="text-2xl font-extrabold text-amber-400 mt-1">{{ $ringkasanKlinik['menunggu_proses'] }} Antrean</p>
    </div>
</div>
@endif

<!-- Recent Requests & Upcoming Holidays -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
    <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h4 class="text-sm font-bold text-slate-900">Pengajuan Cuti Terbaru Saya</h4>
                <p class="text-xs text-slate-500">Pantau progres persetujuan berjenjang atas pengajuan Anda</p>
            </div>
            <a href="{{ route('pengajuan.index') }}" class="text-xs font-bold text-teal-600 hover:text-teal-700">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase border-b border-slate-200/70">
                        <th class="py-3 px-4 font-bold">No. Pengajuan</th>
                        <th class="py-3 px-4 font-bold">Tanggal Cuti</th>
                        <th class="py-3 px-4 font-bold">Alasan</th>
                        <th class="py-3 px-4 font-bold">Status</th>
                        <th class="py-3 px-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayatTerbaru ?? [] as $item)
                        @php
                            $badge = match($item->status) {
                                'disetujui'       => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                'menunggu_atasan' => 'bg-amber-100 text-amber-800 border-amber-200',
                                'menunggu_hr'     => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                'ditolak'         => 'bg-rose-100 text-rose-800 border-rose-200',
                                'dibatalkan'      => 'bg-slate-100 text-slate-600 border-slate-200',
                                default           => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3 px-4 font-mono text-xs font-bold text-slate-800">{{ $item->nomor_pengajuan }}</td>
                            <td class="py-3 px-4 text-xs font-semibold text-slate-700">{{ $item->tanggal_cuti }}</td>
                            <td class="py-3 px-4 text-xs text-slate-600 max-w-xs truncate">{{ $item->alasan }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $badge }}">
                                    {{ strtoupper(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('pengajuan.show', $item->id) }}" class="text-xs font-bold text-teal-600 hover:text-teal-800">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-xs text-slate-400">Belum ada riwayat pengajuan cuti.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden flex flex-col justify-between">
        <div>
            <div class="px-5 py-4 border-b border-slate-100">
                <h4 class="text-sm font-bold text-slate-900">Hari Libur Mendatang</h4>
                <p class="text-xs text-slate-500">Tanggal merah nasional & internal klinik</p>
            </div>
            <div class="p-4 space-y-3">
                @forelse($hariLiburMendatang ?? [] as $libur)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/60">
                        <div>
                            <p class="text-xs font-bold text-slate-800">{{ $libur->nama }}</p>
                            <p class="text-[11px] text-slate-500">{{ $libur->tanggal }}</p>
                        </div>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $libur->jenis === 'nasional' ? 'bg-rose-100 text-rose-700' : 'bg-teal-100 text-teal-700' }}">
                            {{ $libur->jenis }}
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">Tidak ada hari libur mendatang yang tercatat.</p>
                @endforelse
            </div>
        </div>
        <div class="p-4 bg-slate-50 border-t border-slate-100 text-[11px] text-slate-500">
            Catatan: Pengajuan cuti pada hari Sabtu, Minggu, dan tanggal libur di atas otomatis ditolak sistem.
        </div>
    </div>
</div>

<!-- Monitoring Sisa Saldo Cuti Staf (Atasan & Admin HR) -->
@if(in_array($peranKode, ['atasan', 'admin_hr']))
<div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-slate-900 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div>
            <h5 class="text-sm font-bold">Monitoring Sisa Saldo Cuti Staf ({{ date('Y') }})</h5>
            <p class="text-xs text-slate-400">Daftar pantauan kuota cuti tahunan pegawai di bawah supervisi</p>
        </div>
        @if($peranKode === 'admin_hr')
            <a href="{{ route('saldo-cuti.index') }}" class="inline-flex items-center px-3 py-1.5 rounded-xl bg-teal-500 text-slate-950 text-xs font-bold hover:bg-teal-400 transition">
                Kelola Saldo Cuti →
            </a>
        @endif
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs uppercase border-b border-slate-200">
                    <th class="py-3.5 px-4 font-bold">NIP</th>
                    <th class="py-3.5 px-4 font-bold">Nama Staf</th>
                    <th class="py-3.5 px-4 font-bold">Bagian</th>
                    <th class="py-3.5 px-4 font-bold">Jatah Awal</th>
                    <th class="py-3.5 px-4 font-bold">Terpakai</th>
                    <th class="py-3.5 px-4 font-bold">Sisa Cuti</th>
                    <th class="py-3.5 px-4 font-bold">Status Pemanfaatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($stafBawahan as $staf)
                    @php
                        $s = $staf->saldoCuti->first();
                        $awalStaf = $s->jatah_awal ?? 12;
                        $terpakaiStaf = $s->terpakai ?? 0;
                        $sisaStaf = $s->saldo_tersedia ?? 12;
                    @endphp
                    <tr class="hover:bg-slate-50/80">
                        <td class="py-3.5 px-4 font-mono text-xs font-bold text-slate-700">{{ $staf->nomor_induk }}</td>
                        <td class="py-3.5 px-4 font-semibold text-slate-900">{{ $staf->nama }}</td>
                        <td class="py-3.5 px-4 text-xs text-slate-600">{{ $staf->bagianOrganisasi->nama ?? '-' }}</td>
                        <td class="py-3.5 px-4 text-xs font-medium text-slate-700">{{ $awalStaf }} Hari</td>
                        <td class="py-3.5 px-4 text-xs font-medium text-amber-700">{{ $terpakaiStaf }} Hari</td>
                        <td class="py-3.5 px-4 text-xs font-extrabold text-teal-700">{{ $sisaStaf }} Hari</td>
                        <td class="py-3.5 px-4">
                            @if($terpakaiStaf == 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    Belum Pernah Cuti (Follow-Up Liburan!)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Aktif Cuti ({{ $terpakaiStaf }}x)
                                </span>
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
