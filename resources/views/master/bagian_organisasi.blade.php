@extends('layouts.app')
@section('title', 'Bagian Organisasi')

@section('content')
<h1 class="text-2xl font-bold text-slate-900 tracking-tight mb-6">Master Bagian Organisasi</h1>

<form action="{{ route('bagian-organisasi.store') }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-6 mb-8 grid grid-cols-1 sm:grid-cols-3 gap-4 items-end text-sm">
    @csrf
    <div>
        <label class="block font-semibold text-slate-700 mb-1.5">Kode Bagian</label>
        <input type="text" name="kode" placeholder="FRM" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 uppercase outline-none focus:border-cyan-600">
    </div>
    <div>
        <label class="block font-semibold text-slate-700 mb-1.5">Nama Bagian / Instalasi</label>
        <input type="text" name="nama" placeholder="Instalasi Farmasi" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 outline-none focus:border-cyan-600">
    </div>
    <button type="submit" class="px-5 py-2 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-semibold transition">
        Tambah Bagian
    </button>
</form>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
            <tr>
                <th class="py-3.5 px-5 font-semibold">Kode</th>
                <th class="py-3.5 px-5 font-semibold">Nama Bagian Organisasi</th>
                <th class="py-3.5 px-5 font-semibold">Jumlah Pegawai</th>
                <th class="py-3.5 px-5 font-semibold">Status</th>
                <th class="py-3.5 px-5 font-semibold text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @foreach($bagianOrganisasi as $bagian)
            <tr>
                <td class="py-4 px-5 font-semibold text-cyan-700">{{ $bagian->kode }}</td>
                <td class="py-4 px-5 font-semibold text-slate-900">{{ $bagian->nama }}</td>
                <td class="py-4 px-5 text-slate-600 tabular-nums">{{ $bagian->pegawai_count }} Orang</td>
                <td class="py-4 px-5">
                    <span class="inline-block px-3 py-1 rounded-md text-sm font-medium {{ $bagian->aktif ? 'bg-cyan-50 text-cyan-800' : 'bg-slate-100 text-slate-500' }}">
                        {{ $bagian->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="py-4 px-5 text-right">
                    <form action="{{ route('bagian-organisasi.toggle', $bagian->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <button class="text-slate-700 hover:text-cyan-700 font-medium">
                            {{ $bagian->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection