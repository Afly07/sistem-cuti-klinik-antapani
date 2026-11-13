@extends('layouts.app')
@section('title', 'Persetujuan Atasan')

@section('content')
<h1 class="text-2xl font-bold text-slate-900 tracking-tight mb-6">Persetujuan Cuti Bawahan</h1>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                <tr>
                    <th class="py-3.5 px-5 font-semibold">Nomor</th>
                    <th class="py-3.5 px-5 font-semibold">Nama Staf</th>
                    <th class="py-3.5 px-5 font-semibold">Tanggal Cuti</th>
                    <th class="py-3.5 px-5 font-semibold">Alasan</th>
                    <th class="py-3.5 px-5 font-semibold">Keputusan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($antreanAtasan as $ajuan)
                <tr>
                    <td class="py-4 px-5 font-medium tabular-nums">{{ $ajuan->nomor_pengajuan }}</td>
                    <td class="py-4 px-5 font-semibold text-slate-900">{{ $ajuan->pegawai->nama ?? '-' }}</td>
                    <td class="py-4 px-5 text-slate-800 tabular-nums">{{ $ajuan->tanggal_cuti }}</td>
                    <td class="py-4 px-5 text-slate-600">{{ $ajuan->alasan }}</td>
                    <td class="py-4 px-5">
                        <form action="{{ route('pengajuan.keputusanAtasan', $ajuan->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <input type="text" name="catatan" placeholder="Catatan..." class="rounded-lg border border-slate-300 px-3 py-2 text-sm w-44 focus:border-cyan-600 outline-none">
                            <button name="keputusan" value="disetujui" class="px-4 py-2 rounded-lg bg-cyan-700 hover:bg-cyan-800 text-white text-sm font-semibold">Setujui</button>
                            <button name="keputusan" value="ditolak" class="px-4 py-2 rounded-lg border border-red-300 text-red-700 hover:bg-red-50 text-sm font-semibold">Tolak</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-10 text-center text-base text-slate-400">Tidak ada pengajuan yang menunggu keputusan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection