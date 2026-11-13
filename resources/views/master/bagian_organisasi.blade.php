@extends('layouts.app')
@section('title', 'Master Bagian Organisasi')
@section('subtitle', 'Kelola struktur divisi dan instalasi pelayanan pada Klinik Utama Medika Antapani')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <div class="lg:col-span-4">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="px-5 py-4 bg-slate-900 text-white">
                <h6 class="text-sm font-bold">Tambah Bagian Organisasi</h6>
                <p class="text-xs text-slate-400">Unit instalasi atau departemen klinik</p>
            </div>
            <form action="{{ route('bagian-organisasi.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kode Bagian</label>
                    <input type="text" name="kode" placeholder="Contoh: IGD, FRM, LAB" maxlength="20" required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm uppercase outline-none focus:border-teal-600">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Bagian / Instalasi</label>
                    <input type="text" name="nama" placeholder="Contoh: Instalasi Gawat Darurat" maxlength="100" required
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm outline-none focus:border-teal-600">
                </div>
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs shadow-sm transition">
                    Simpan Bagian Organisasi
                </button>
            </form>
        </div>
    </div>

    <div class="lg:col-span-8">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h6 class="text-sm font-bold text-slate-900">Daftar Bagian Organisasi Klinik</h6>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 text-xs uppercase border-b border-slate-200">
                            <th class="py-3.5 px-4 font-bold">Kode</th>
                            <th class="py-3.5 px-4 font-bold">Nama Bagian Organisasi</th>
                            <th class="py-3.5 px-4 font-bold">Jumlah Pegawai</th>
                            <th class="py-3.5 px-4 font-bold">Status</th>
                            <th class="py-3.5 px-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($bagianOrganisasi as $bagian)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 px-4 font-mono text-xs font-bold text-teal-700">{{ $bagian->kode }}</td>
                            <td class="py-3.5 px-4 text-xs font-bold text-slate-900">{{ $bagian->nama }}</td>
                            <td class="py-3.5 px-4 text-xs text-slate-600">{{ $bagian->pegawai_count }} Pegawai</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $bagian->aktif ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $bagian->aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <form action="{{ route('bagian-organisasi.toggle', $bagian->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button class="px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold">
                                        {{ $bagian->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-8 text-center text-xs text-slate-400">Belum ada bagian organisasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
