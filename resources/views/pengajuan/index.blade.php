@extends('layouts.app')
@section('title', 'Riwayat Pengajuan Cuti')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Riwayat Pengajuan Saya</h1>
    <a href="{{ route('pengajuan.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold transition">
        Ajukan Cuti Baru
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                <tr>
                    <th class="py-3.5 px-5 font-semibold">Nomor</th>
                    <th class="py-3.5 px-5 font-semibold">Tanggal Cuti</th>
                    <th class="py-3.5 px-5 font-semibold">Alasan</th>
                    <th class="py-3.5 px-5 font-semibold">Status</th>
                    <th class="py-3.5 px-5 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($riwayat as $item)
                <tr class="hover:bg-slate-50/70">
                    <td class="py-4 px-5 font-semibold tabular-nums">
                        <a href="{{ route('pengajuan.show', $item->id) }}" class="text-cyan-700 hover:underline">
                            {{ $item->nomor_pengajuan }}
                        </a>
                    </td>
                    <td class="py-4 px-5 text-slate-800 tabular-nums">{{ $item->tanggal_cuti }}</td>
                    <td class="py-4 px-5 text-slate-600">{{ $item->alasan }}</td>
                    <td class="py-4 px-5">
                        <span class="inline-block px-3 py-1 rounded-md text-sm font-medium bg-slate-100 text-slate-800 capitalize">
                            {{ str_replace('_', ' ', $item->status) }}
                        </span>
                    </td>
                    <td class="py-4 px-5 text-right space-x-3 whitespace-nowrap">
                        <a href="{{ route('pengajuan.show', $item->id) }}" class="text-slate-600 hover:text-slate-900 font-medium">Detail</a>
                        @can('ajukanDraf', $item)
                        <form action="{{ route('pengajuan.ajukanDraf', $item->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button class="text-cyan-700 hover:underline font-semibold">Kirim</button>
                        </form>
                        @endcan
                        @can('batalkan', $item)
                        <form action="{{ route('pengajuan.batalkan', $item->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button class="text-red-600 hover:underline font-semibold">Batalkan</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-10 text-center text-base text-slate-400">Belum ada riwayat pengajuan cuti.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection