@extends('layouts.app')
@section('title', 'Master Pengguna & Pegawai')
@section('subtitle', 'Kelola data kepegawaian, akun login pengguna, peran akses, dan relasi atasan penyetuju')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Form Tambah Pegawai -->
    <div class="lg:col-span-4">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="px-5 py-4 bg-slate-900 text-white">
                <h6 class="text-sm font-bold">Registrasi Pegawai & Pengguna Baru</h6>
                <p class="text-xs text-slate-400">Otomatis membuat akun login & saldo cuti awal</p>
            </div>
            <form action="{{ route('pegawai.store') }}" method="POST" class="p-5 space-y-3.5">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">NIP</label>
                        <input type="text" name="nomor_induk" placeholder="KAR-007" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Jatah Cuti</label>
                        <input type="number" name="jatah_cuti" value="12" min="0" max="30" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Nama Lengkap & Gelar</label>
                    <input type="text" name="nama" placeholder="Nama lengkap pegawai" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Email Login</label>
                    <input type="email" name="email" placeholder="nama@klinikantapani.id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Kata Sandi</label>
                        <input type="password" name="password" value="password" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" value="{{ date('Y-m-d') }}" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Peran Sistem</label>
                    <select name="peran_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                        @foreach($daftarPeran as $peran)
                            <option value="{{ $peran->id }}">{{ $peran->nama }} ({{ $peran->kode }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Bagian Organisasi</label>
                    <select name="bagian_organisasi_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                        @foreach($daftarBagian as $bagian)
                            <option value="{{ $bagian->id }}">{{ $bagian->nama }} ({{ $bagian->kode }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Atasan Langsung (Opsional untuk Atasan/HR)</label>
                    <select name="atasan_id" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs">
                        <option value="">-- Langsung ke Admin HR --</option>
                        @foreach($daftarAtasan as $atasan)
                            <option value="{{ $atasan->id }}">{{ $atasan->nama }} ({{ $atasan->nomor_induk }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs shadow-sm transition">
                    Simpan Pegawai & Akun
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Direktori Pegawai -->
    <div class="lg:col-span-8">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h6 class="text-sm font-bold text-slate-900">Direktori Pegawai Klinik Utama Medika Antapani</h6>
                    <p class="text-xs text-slate-500">Total {{ $daftarPegawai->count() }} pegawai terdaftar</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 text-xs uppercase border-b border-slate-200">
                            <th class="py-3.5 px-4 font-bold">NIP & Nama</th>
                            <th class="py-3.5 px-4 font-bold">Bagian & Peran</th>
                            <th class="py-3.5 px-4 font-bold">Atasan Langsung</th>
                            <th class="py-3.5 px-4 font-bold">Status</th>
                            <th class="py-3.5 px-4 font-bold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($daftarPegawai as $p)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900 text-xs">{{ $p->nama }}</p>
                                <p class="text-[11px] text-slate-500 font-mono">{{ $p->nomor_induk }} • {{ $p->pengguna->email ?? '-' }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="text-xs font-semibold text-slate-800">{{ $p->bagianOrganisasi->nama ?? '-' }}</p>
                                <span class="inline-block mt-0.5 px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700">
                                    {{ $p->pengguna->peran->nama ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-600">{{ $p->atasan->nama ?? 'Langsung HRD' }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $p->aktif ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $p->aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <form action="{{ route('pegawai.toggle', $p->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold">
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
    </div>
</div>
@endsection
