@extends('layouts.app')
@section('title', 'Data Master Hari Libur')
@section('subtitle', 'Kelola kalender libur nasional dan libur internal Klinik Utama Medika Antapani sebagai acuan validasi cuti')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <div class="lg:col-span-4">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="px-5 py-4 bg-slate-900 text-white">
                <h6 class="text-sm font-bold">Tambah Hari Libur Baru</h6>
                <p class="text-xs text-slate-400">Tanggal aktif otomatis memblokir pengajuan cuti</p>
            </div>
            <form action="{{ route('hari-libur.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Libur</label>
                    <input type="date" name="tanggal" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-teal-600" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Hari Libur</label>
                    <input type="text" name="nama" placeholder="Contoh: Hari Raya Idul Fitri" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-teal-600" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jenis Libur</label>
                    <select name="jenis" class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-teal-600" required>
                        <option value="nasional">Libur Nasional</option>
                        <option value="perusahaan">Libur Perusahaan / Klinik</option>
                    </select>
                </div>
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs shadow-sm transition">
                    Simpan Hari Libur
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-8">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h6 class="text-sm font-bold text-slate-900">Daftar Kalender Hari Libur</h6>
                    <p class="text-xs text-slate-500">Total {{ $hariLibur->count() }} tanggal terdaftar</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 text-xs uppercase border-b border-slate-200">
                            <th class="py-3.5 px-4 font-bold">Tanggal</th>
                            <th class="py-3.5 px-4 font-bold">Nama Hari Libur</th>
                            <th class="py-3.5 px-4 font-bold">Jenis</th>
                            <th class="py-3.5 px-4 font-bold">Status</th>
                            <th class="py-3.5 px-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($hariLibur as $libur)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 px-4 font-mono text-xs font-bold text-slate-800">{{ $libur->tanggal }}</td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-slate-900">{{ $libur->nama }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase {{ $libur->jenis === 'nasional' ? 'bg-rose-100 text-rose-800' : 'bg-teal-100 text-teal-800' }}">
                                    {{ $libur->jenis }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $libur->aktif ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $libur->aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <form action="{{ route('hari-libur.toggle', $libur->id) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold">
                                            {{ $libur->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('hari-libur.destroy', $libur->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-semibold">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-8 text-center text-xs text-slate-400">Belum ada data hari libur.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
