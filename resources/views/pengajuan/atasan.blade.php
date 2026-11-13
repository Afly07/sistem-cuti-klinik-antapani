@extends('layouts.app')
@section('title', 'Persetujuan Atasan')

@section('content')
<h1 class="text-xl font-bold text-slate-900 mb-6">Persetujuan Cuti Bawahan</h1>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4 font-semibold">Nomor</th>
                    <th class="py-3 px-4 font-semibold">Nama Staf</th>
                    <th class="py-3 px-4 font-semibold">Tanggal</th>
                    <th class="py-3 px-4 font-semibold">Alasan</th>
                    <th class="py-3 px-4 font-semibold">Keputusan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($antreanAtasan as $ajuan)
                <tr>
                    <td class="py-3 px-4 font-medium">{{ $ajuan->nomor_pengajuan }}</td>
                    <td class="py-3 px-4 font-medium text-slate-900">{{ $ajuan->pegawai->nama ?? '-' }}</td>
                    <td class="py-3 px-4 text-slate-700">{{ $ajuan->tanggal_cuti }}</td>
                    <td class="py-3 px-4 text-slate-600">{{ $ajuan->alasan }}</td>
                    <td class="py-3 px-4">
                        <form action="{{ route('pengajuan.keputusanAtasan', $ajuan->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <input type="text" name="catatan" placeholder="Catatan..." class="rounded-lg border border-slate-300 px-3 py-1.5 text-sm w-40">
                            <button name="keputusan" value="disetujui" class="px-3 py-1.5 rounded-lg bg-teal-700 hover:bg-teal-800 text-white text-sm font-medium">Setujui</button>
                            <button name="keputusan" value="ditolak" class="px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium">Tolak</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-400">Tidak ada pengajuan menunggu persetujuan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection