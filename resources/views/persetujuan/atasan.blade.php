{{-- resources/views/persetujuan/atasan.blade.php --}}
@extends('layouts.app')
@section('title', 'Persetujuan Cuti Bawahan (Tahap Atasan)')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 bg-slate-900 text-white"><h5 class="text-sm font-bold">Daftar Pengajuan Cuti Bawahan (Tahap Atasan)</h5></div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-slate-50 text-xs uppercase border-b border-slate-200">
                    <th class="py-3 px-4">No. Pengajuan</th>
                    <th class="py-3 px-4">Nama Staf</th>
                    <th class="py-3 px-4">Tanggal Cuti</th>
                    <th class="py-3 px-4">Alasan</th>
                    <th class="py-3 px-4">Aksi Keputusan Atasan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($antreanAtasan as $ajuan)
                <tr>
                    <td class="py-3 px-4 font-mono text-xs font-bold">{{ $ajuan->nomor_pengajuan }}</td>
                    <td class="py-3 px-4 text-xs font-bold">{{ $ajuan->pegawai->nama ?? '-' }}</td>
                    <td class="py-3 px-4 text-xs">{{ $ajuan->tanggal_cuti }}</td>
                    <td class="py-3 px-4 text-xs">{{ $ajuan->alasan }}</td>
                    <td class="py-3 px-4">
                        <form action="{{ route('pengajuan.keputusanAtasan', $ajuan->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <input type="text" name="catatan" class="rounded-xl border border-slate-300 px-3 py-1 text-xs" placeholder="Catatan...">
                            <button name="keputusan" value="disetujui" class="px-3 py-1 rounded-xl bg-emerald-600 text-white text-xs font-bold">Setujui</button>
                            <button name="keputusan" value="ditolak" class="px-3 py-1 rounded-xl bg-rose-600 text-white text-xs font-bold">Tolak</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-6 text-center text-xs text-slate-400">Tidak ada pengajuan bawahan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection