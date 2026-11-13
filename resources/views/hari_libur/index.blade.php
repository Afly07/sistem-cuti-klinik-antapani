@extends('layouts.app')
@section('title', 'Master Hari Libur')

@section('content')
<h1 class="text-2xl font-bold text-slate-900 tracking-tight mb-6">Kalender Hari Libur</h1>

<form action="{{ route('hari-libur.store') }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-6 mb-8 grid grid-cols-1 sm:grid-cols-4 gap-4 items-end text-sm">
    @csrf
    <div>
        <label class="block font-semibold text-slate-700 mb-1.5">Tanggal Libur</label>
        <input type="date" name="tanggal" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 outline-none focus:border-cyan-600">
    </div>
    <div>
        <label class="block font-semibold text-slate-700 mb-1.5">Nama Hari Libur</label>
        <input type="text" name="nama" placeholder="Contoh: Tahun Baru" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 outline-none focus:border-cyan-600">
    </div>
    <div>
        <label class="block font-semibold text-slate-700 mb-1.5">Jenis Libur</label>
        <select name="jenis" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 outline-none focus:border-cyan-600">
            <option value="nasional">Nasional</option>
            <option value="perusahaan">Perusahaan / Klinik</option>
        </select>
    </div>
    <button type="submit" class="px-5 py-2 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-semibold transition">
        Tambah Libur
    </button>
</form>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-left text-sm">
        <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
            <tr>
                <th class="py-3.5 px-5 font-semibold">Tanggal</th>
                <th class="py-3.5 px-5 font-semibold">Nama Hari Libur</th>
                <th class="py-3.5 px-5 font-semibold">Jenis</th>
                <th class="py-3.5 px-5 font-semibold">Status</th>
                <th class="py-3.5 px-5 font-semibold text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse($hariLibur as $libur)
            <tr>
                <td class="py-4 px-5 font-semibold tabular-nums text-slate-900">{{ $libur->tanggal }}</td>
                <td class="py-4 px-5 text-slate-800">{{ $libur->nama }}</td>
                <td class="py-4 px-5 capitalize text-slate-600">{{ $libur->jenis }}</td>
                <td class="py-4 px-5">
                    <span class="inline-block px-3 py-1 rounded-md text-sm font-medium {{ $libur->aktif ? 'bg-cyan-50 text-cyan-800' : 'bg-slate-100 text-slate-500' }}">
                        {{ $libur->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="py-4 px-5 text-right space-x-3">
                    <form action="{{ route('hari-libur.toggle', $libur->id) }}" method="POST" class="inline">
                        @csrf @method('PATCH')
                        <button class="text-slate-600 hover:text-slate-900 font-medium">{{ $libur->aktif ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                    </form>
                    <form action="{{ route('hari-libur.destroy', $libur->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline font-semibold">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-10 text-center text-base text-slate-400">Belum ada data hari libur.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection