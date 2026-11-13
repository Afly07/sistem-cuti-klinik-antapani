@extends('layouts.app')
@section('title', 'Riwayat & Status Pengajuan Cuti Saya')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="text-sm font-bold text-slate-900">Riwayat & Status Pengajuan Cuti Saya</h3>
        <a href="{{ route('pengajuan.create') }}" class="px-4 py-2 rounded-xl bg-teal-600 text-white text-xs font-bold">+ Ajukan Cuti Baru</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs uppercase border-b border-slate-200">
                    <th class="py-3 px-4">No. Pengajuan</th>
                    <th class="py-3 px-4">Tanggal Cuti</th>
                    <th class="py-3 px-4">Alasan</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($riwayat as $item)
                <tr>
                    <td class="py-3 px-4 font-mono text-xs font-bold">
                        <a href="{{ route('pengajuan.show', $item->id) }}" class="text-teal-700 underline">{{ $item->nomor_pengajuan }}</a>
                    </td>
                    <td class="py-3 px-4 text-xs font-bold">{{ $item->tanggal_cuti }}</td>
                    <td class="py-3 px-4 text-xs text-slate-600">{{ $item->alasan }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-slate-100 text-slate-700">{{ str_replace('_', ' ', $item->status) }}</span>
                    </td>
                    <td class="py-3 px-4 text-right space-x-1">
                        <a href="{{ route('pengajuan.show', $item->id) }}" class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">Detail</a>
                        @can('ajukanDraf', $item)
                        <form action="{{ route('pengajuan.ajukanDraf', $item->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button class="px-2.5 py-1 rounded-lg bg-teal-600 text-white text-xs font-semibold">Kirim</button>
                        </form>
                        @endcan
                        @can('batalkan', $item)
                        <form action="{{ route('pengajuan.batalkan', $item->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button class="px-2.5 py-1 rounded-lg bg-rose-600 text-white text-xs font-semibold">Batalkan</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-xs text-slate-400">Belum ada data pengajuan cuti.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection