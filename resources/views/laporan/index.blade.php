@extends('layouts.app')
@section('title', 'Laporan & Rekapitulasi Cuti Tahunan')
@section('subtitle', 'Analisis riwayat pengajuan cuti seluruh pegawai Klinik Utama Medika Antapani beserta fitur cetak dan ekspor CSV')

@section('content')
<!-- Statistik Ringkas -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
        <span class="text-[11px] font-bold uppercase text-slate-400">Total Pengajuan</span>
        <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ $statistik['total'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-emerald-200 p-4 shadow-sm">
        <span class="text-[11px] font-bold uppercase text-emerald-700">Disetujui Akhir (HR)</span>
        <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $statistik['disetujui'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-amber-200 p-4 shadow-sm">
        <span class="text-[11px] font-bold uppercase text-amber-700">Dalam Proses Verifikasi</span>
        <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $statistik['menunggu'] }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-rose-200 p-4 shadow-sm">
        <span class="text-[11px] font-bold uppercase text-rose-700">Ditolak / Dibatalkan</span>
        <p class="text-2xl font-extrabold text-rose-600 mt-1">{{ $statistik['ditolak_batal'] }}</p>
    </div>
</div>

<!-- Filter & Tombol Ekspor -->
<div class="no-print bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm mb-6 flex flex-col lg:flex-row lg:items-end justify-between gap-4">
    <form action="{{ route('laporan.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 flex-1">
        <div>
            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Status Pengajuan</label>
            <select name="status" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                <option value="semua">Semua Status</option>
                @foreach(['draf', 'menunggu_atasan', 'menunggu_hr', 'disetujui', 'ditolak', 'dibatalkan'] as $st)
                    <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ strtoupper(str_replace('_', ' ', $st)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Bagian Organisasi</label>
            <select name="bagian_organisasi_id" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                <option value="">Semua Bagian / Instalasi</option>
                @foreach($daftarBagian as $b)
                    <option value="{{ $b->id }}" {{ (string) request('bagian_organisasi_id') === (string) $b->id ? 'selected' : '' }}>{{ $b->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Tahun Cuti</label>
            <input type="number" name="tahun" value="{{ request('tahun', date('Y')) }}" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition">Terapkan Filter</button>
            <a href="{{ route('laporan.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold">Reset</a>
        </div>
    </form>

    <div class="flex items-center gap-2">
        <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold border border-slate-300 transition">
            Cetak Laporan
        </button>
        <a href="{{ route('pengajuan.eksporCsv', request()->query()) }}" class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-sm transition">
            Unduh Ekspor CSV
        </a>
    </div>
</div>

<!-- Tabel Rekapitulasi -->
<div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
    <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
        <h5 class="text-sm font-bold">Tabel Rekapitulasi Pengajuan Cuti Pegawai</h5>
        <span class="text-xs text-slate-400">Dicetak: {{ date('d/m/Y H:i') }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs uppercase border-b border-slate-200">
                    <th class="py-3.5 px-4 font-bold">No. Pengajuan</th>
                    <th class="py-3.5 px-4 font-bold">NIP</th>
                    <th class="py-3.5 px-4 font-bold">Nama Pegawai</th>
                    <th class="py-3.5 px-4 font-bold">Bagian Organisasi</th>
                    <th class="py-3.5 px-4 font-bold">Tanggal Cuti</th>
                    <th class="py-3.5 px-4 font-bold">Alasan</th>
                    <th class="py-3.5 px-4 font-bold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($dataCuti as $row)
                <tr class="hover:bg-slate-50/80">
                    <td class="py-3 px-4 font-mono text-xs font-bold text-slate-800">
                        <a href="{{ route('pengajuan.show', $row->id) }}" class="hover:text-teal-600 underline">{{ $row->nomor_pengajuan }}</a>
                    </td>
                    <td class="py-3 px-4 font-mono text-xs text-slate-600">{{ $row->pegawai->nomor_induk ?? '-' }}</td>
                    <td class="py-3 px-4 text-xs font-bold text-slate-900">{{ $row->pegawai->nama ?? '-' }}</td>
                    <td class="py-3 px-4 text-xs text-slate-600">{{ $row->pegawai->bagianOrganisasi->nama ?? '-' }}</td>
                    <td class="py-3 px-4 text-xs font-bold text-teal-700">{{ $row->tanggal_cuti }}</td>
                    <td class="py-3 px-4 text-xs text-slate-600 max-w-xs">{{ $row->alasan }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-slate-100 text-slate-700">
                            {{ str_replace('_', ' ', $row->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center text-xs text-slate-400">Tidak ada data pengajuan cuti yang sesuai filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
