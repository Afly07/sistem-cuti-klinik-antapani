@extends('layouts.app')
@section('title', 'Data Pegawai')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Data Pengguna & Pegawai</h1>
    <a href="{{ route('bagian-organisasi.index') }}" class="text-sm font-semibold text-cyan-700 hover:underline">
        Kelola Bagian Organisasi →
    </a>
</div>

<!-- Form Tambah Pegawai -->
<form action="{{ route('pegawai.store') }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-6 mb-8">
    @csrf
    <h2 class="text-base font-bold text-slate-900 mb-4">Tambah Pegawai Baru</h2>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
        <div>
            <label class="block font-semibold text-slate-700 mb-1.5">NIP</label>
            <input type="text" name="nomor_induk" placeholder="KAR-007" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 outline-none focus:border-cyan-600">
        </div>
        <div>
            <label class="block font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
            <input type="text" name="nama" placeholder="Nama pegawai" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 outline-none focus:border-cyan-600">
        </div>
        <div>
            <label class="block font-semibold text-slate-700 mb-1.5">Email Login</label>
            <input type="email" name="email" placeholder="nama@klinikantapani.id" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 outline-none focus:border-cyan-600">
        </div>
        <div>
            <label class="block font-semibold text-slate-700 mb-1.5">Kata Sandi Awal</label>
            <input type="password" name="password" value="password" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 outline-none focus:border-cyan-600">
        </div>
        <div>
            <label class="block font-semibold text-slate-700 mb-1.5">Peran</label>
            <select name="peran_id" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 outline-none focus:border-cyan-600">
                @foreach($daftarPeran as $peran)
                    <option value="{{ $peran->id }}">{{ $peran->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-semibold text-slate-700 mb-1.5">Bagian Organisasi</label>
            <select name="bagian_organisasi_id" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 outline-none focus:border-cyan-600">
                @foreach($daftarBagian as $bagian)
                    <option value="{{ $bagian->id }}">{{ $bagian->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-semibold text-slate-700 mb-1.5">Atasan Langsung</label>
            <select name="atasan_id" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 outline-none focus:border-cyan-600">
                <option value="">Langsung ke Admin HR</option>
                @foreach($daftarAtasan as $atasan)
                    <option value="{{ $atasan->id }}">{{ $atasan->nama }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block font-semibold text-slate-700 mb-1.5">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" value="{{ date('Y-m-d') }}" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 outline-none focus:border-cyan-600">
        </div>
        <div>
            <label class="block font-semibold text-slate-700 mb-1.5">Jatah Cuti Tahunan</label>
            <input type="number" name="jatah_cuti" value="12" min="0" max="30" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 tabular-nums outline-none focus:border-cyan-600">
        </div>
    </div>
    <div class="mt-5">
        <button type="submit" class="px-5 py-2.5 rounded-lg bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold transition">
            Simpan Pegawai
        </button>
    </div>
</form>

<!-- Tabel Pegawai -->
<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-700 border-b border-slate-200">
                <tr>
                    <th class="py-3.5 px-5 font-semibold">NIP</th>
                    <th class="py-3.5 px-5 font-semibold">Nama & Email</th>
                    <th class="py-3.5 px-5 font-semibold">Bagian & Peran</th>
                    <th class="py-3.5 px-5 font-semibold">Atasan</th>
                    <th class="py-3.5 px-5 font-semibold">Status</th>
                    <th class="py-3.5 px-5 font-semibold text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($daftarPegawai as $p)
                <tr class="hover:bg-slate-50/70">
                    <td class="py-4 px-5 text-slate-600 tabular-nums">{{ $p->nomor_induk }}</td>
                    <td class="py-4 px-5">
                        <p class="font-semibold text-slate-900">{{ $p->nama }}</p>
                        <p class="text-slate-500">{{ $p->pengguna->email ?? '-' }}</p>
                    </td>
                    <td class="py-4 px-5">
                        <p class="text-slate-800">{{ $p->bagianOrganisasi->nama ?? '-' }}</p>
                        <p class="text-cyan-700 font-medium">{{ $p->pengguna->peran->nama ?? '-' }}</p>
                    </td>
                    <td class="py-4 px-5 text-slate-600">{{ $p->atasan->nama ?? 'Admin HR' }}</td>
                    <td class="py-4 px-5">
                        <span class="inline-block px-3 py-1 rounded-md text-sm font-medium {{ $p->aktif ? 'bg-cyan-50 text-cyan-800' : 'bg-slate-100 text-slate-500' }}">
                            {{ $p->aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="py-4 px-5 text-right">
                        <form action="{{ route('pegawai.toggle', $p->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button class="text-slate-700 hover:text-cyan-700 font-medium">
                                {{ $p->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection