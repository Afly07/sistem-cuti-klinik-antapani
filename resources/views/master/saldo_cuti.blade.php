@extends('layouts.app')
@section('title', 'Kelola Saldo Cuti')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Saldo Cuti Tahunan ({{ $tahun }})</h1>
    <form action="{{ route('saldo-cuti.inisialisasi') }}" method="POST">
        @csrf
        <input type="hidden" name="tahun" value="{{ $tahun }}">
        <button type="submit" class="px-5 py-2.5 rounded-lg bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold transition">
            Sinkronkan Saldo {{ $tahun }}
        </button>
    </form>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                <tr>
                    <th class="py-3.5 px-5 font-semibold">NIP</th>
                    <th class="py-3.5 px-5 font-semibold">Nama Pegawai</th>
                    <th class="py-3.5 px-5 font-semibold">Bagian</th>
                    <th class="py-3.5 px-5 font-semibold">Terpakai</th>
                    <th class="py-3.5 px-5 font-semibold">Tersedia</th>
                    <th class="py-3.5 px-5 font-semibold">Atur Jatah Awal & Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($daftarSaldo as $s)
                <tr>
                    <td class="py-4 px-5 text-slate-600 tabular-nums">{{ $s->pegawai->nomor_induk ?? '-' }}</td>
                    <td class="py-4 px-5 font-semibold text-slate-900">{{ $s->pegawai->nama ?? '-' }}</td>
                    <td class="py-4 px-5 text-slate-600">{{ $s->pegawai->bagianOrganisasi->nama ?? '-' }}</td>
                    <td class="py-4 px-5 text-orange-600 font-semibold tabular-nums">{{ $s->terpakai }} Hari</td>
                    <td class="py-4 px-5 text-cyan-700 font-bold tabular-nums">{{ $s->saldo_tersedia }} Hari</td>
                    <td class="py-4 px-5">
                        <form action="{{ route('saldo-cuti.update', $s->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <input type="number" name="jatah_awal" value="{{ $s->jatah_awal }}" min="0" max="30" class="w-20 rounded-lg border border-slate-300 px-3 py-2 text-sm tabular-nums text-center">
                            <input type="text" name="catatan" value="{{ $s->catatan }}" placeholder="Catatan..." class="w-48 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            <button type="submit" class="px-4 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold">Simpan</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-10 text-center text-base text-slate-400">Belum ada data saldo cuti.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection