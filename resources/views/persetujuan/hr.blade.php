{{-- resources/views/persetujuan/hr.blade.php --}}
@extends('layouts.app')
@section('title', 'Persetujuan Akhir Admin HR')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
        <h5 class="text-sm font-bold">Antrean Persetujuan Akhir Admin HR (Potong Saldo Cuti)</h5>
        <a href="{{ route('pengajuan.eksporCsv') }}" class="px-3 py-1.5 rounded-xl bg-amber-500 text-slate-950 text-xs font-bold">Ekspor CSV</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs uppercase border-b border-slate-200">
                    <th class="py-3 px-4">No. Pengajuan</th>
                    <th class="py-3 px-4">Nama Pegawai</th>
                    <th class="py-3 px-4">Tanggal Cuti</th>
                    <th class="py-3 px-4">Alasan</th>
                    <th class="py-3 px-4">Keputusan Akhir HR</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($antreanHr as $ajuan)
                <tr>
                    <td class="py-3 px-4 font-mono text-xs font-bold">{{ $ajuan->nomor_pengajuan }}</td>
                    <td class="py-3 px-4 text-xs font-bold">{{ $ajuan->pegawai->nama ?? '-' }}</td>
                    <td class="py-3 px-4 text-xs">{{ $ajuan->tanggal_cuti }}</td>
                    <td class="py-3 px-4 text-xs">{{ $ajuan->alasan }}</td>
                    <td class="py-3 px-4">
                        <form action="{{ route('pengajuan.keputusanHr', $ajuan->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <input type="text" name="catatan" class="rounded-xl border border-slate-300 px-3 py-1 text-xs" placeholder="Catatan HR...">
                            <button name="keputusan" value="disetujui" class="px-3 py-1 rounded-xl bg-teal-600 text-white text-xs font-bold">Setujui & Potong Saldo</button>
                            <button name="keputusan" value="ditolak" class="px-3 py-1 rounded-xl bg-rose-600 text-white text-xs font-bold">Tolak</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-6 text-center text-xs text-slate-400">Tidak ada antrean persetujuan HR.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection