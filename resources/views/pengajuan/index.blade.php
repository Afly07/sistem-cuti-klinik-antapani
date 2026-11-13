@extends('layouts.app')
@section('title', 'Riwayat Pengajuan Cuti')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-slate-900">Riwayat Pengajuan Cuti</h1>
    <a href="{{ route('pengajuan.create') }}" class="px-4 py-2 rounded-lg bg-teal-700 hover:bg-teal-800 text-white text-sm font-semibold">
        + Ajukan Cuti
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-600 border-b border-slate-200">
                <tr>
                    <th class="py-3 px-4 font-semibold">Nomor</th>
                    <th class="py-3 px-4 font-semibold">Tanggal Cuti</th>
                    <th class="py-3 px-4 font-semibold">Alasan</th>
                    <th class="py-3 px-4 font-semibold">Status</th>
                    <th class="py-3 px-4 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($riwayat as $item)
                <tr>
                    <td class="py-3 px-4 font-medium text-slate-900">
                        <a href="{{ route('pengajuan.show', $item->id) }}" class="text-teal-700 hover:underline">{{ $item->nomor_pengajuan }}</a>
                    </td>
                    <td class="py-3 px-4 text-slate-700">{{ $item->tanggal_cuti }}</td>
                    <td class="py-3 px-4 text-slate-600">{{ $item->alasan }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700 capitalize">
                            {{ str_replace('_', ' ', $item->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right space-x-2">
                        @can('ajukanDraf', $item)
                        <form action="{{ route('pengajuan.ajukanDraf', $item->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button class="text-teal-700 hover:underline font-medium">Kirim</button>
                        </form>
                        @endcan
                        @can('batalkan', $item)
                        <form action="{{ route('pengajuan.batalkan', $item->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button class="text-rose-600 hover:underline font-medium">Batalkan</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-400">Belum ada data pengajuan cuti.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection