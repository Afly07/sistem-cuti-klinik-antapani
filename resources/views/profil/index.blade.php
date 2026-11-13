@extends('layouts.app')
@section('title', 'Profil Pegawai & Rincian Saldo Cuti')
@section('subtitle', 'Informasi identitas kepegawaian, atasan penyetuju, serta keamanan akun Anda')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <div class="lg:col-span-7 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-900 text-white">
                <h3 class="text-sm font-bold">Biodata Kepegawaian Klinik Utama Medika Antapani</h3>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-xs text-slate-400 block">Nama Lengkap</span>
                    <span class="font-bold text-slate-900">{{ $pegawai->nama ?? $pengguna->nama }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Nomor Induk Pegawai (NIP)</span>
                    <span class="font-mono font-bold text-teal-700">{{ $pegawai->nomor_induk ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Alamat Email Login</span>
                    <span class="font-semibold text-slate-800">{{ $pengguna->email }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Peran Hak Akses</span>
                    <span class="font-bold text-slate-800 uppercase">{{ $pengguna->peran->nama ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Bagian Organisasi / Instalasi</span>
                    <span class="font-semibold text-slate-800">{{ $pegawai->bagianOrganisasi->nama ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Atasan Penyetuju Cuti</span>
                    <span class="font-semibold text-slate-800">{{ $pegawai->atasan->nama ?? 'Langsung ke Admin HR' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Tanggal Mulai Bergabung</span>
                    <span class="font-semibold text-slate-800">{{ $pegawai->tanggal_masuk ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 block">Jatah Cuti Reguler</span>
                    <span class="font-bold text-teal-700">{{ $pegawai->jatah_cuti ?? 12 }} Hari / Tahun</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h4 class="text-sm font-bold text-slate-900">Riwayat Saldo Cuti Tahunan Saya</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 text-xs uppercase border-b border-slate-200">
                            <th class="py-3 px-4 font-bold">Tahun</th>
                            <th class="py-3 px-4 font-bold">Jatah Awal</th>
                            <th class="py-3 px-4 font-bold">Terpakai</th>
                            <th class="py-3 px-4 font-bold">Saldo Tersedia</th>
                            <th class="py-3 px-4 font-bold">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($riwayatSaldo as $rs)
                        <tr>
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $rs->tahun }}</td>
                            <td class="py-3 px-4 text-xs">{{ $rs->jatah_awal }} Hari</td>
                            <td class="py-3 px-4 text-xs text-amber-700 font-semibold">{{ $rs->terpakai }} Hari</td>
                            <td class="py-3 px-4 text-xs text-teal-700 font-extrabold">{{ $rs->saldo_tersedia }} Hari</td>
                            <td class="py-3 px-4 text-xs text-slate-500">{{ $rs->catatan ?: '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="lg:col-span-5">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-900 text-white">
                <h3 class="text-sm font-bold">Perbarui Kata Sandi Akun</h3>
            </div>
            <form action="{{ route('profil.password') }}" method="POST" class="p-6 space-y-4">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Kata Sandi Baru</label>
                    <input type="password" name="password_baru" required minlength="6" placeholder="Minimal 6 karakter"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_baru_confirmation" required minlength="6" placeholder="Ulangi kata sandi baru"
                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-sm">
                </div>
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs shadow-sm transition">
                    Simpan Kata Sandi Baru
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
