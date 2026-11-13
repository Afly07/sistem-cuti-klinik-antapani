@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<h1 class="text-2xl font-bold text-slate-900 tracking-tight mb-6">Profil Pegawai</h1>

<div class="grid grid-cols-1 md:grid-cols-12 gap-8">
    <div class="md:col-span-7 bg-white rounded-xl border border-slate-200 p-6 grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
        <div>
            <p class="text-slate-500 mb-1">Nama Lengkap</p>
            <p class="text-base font-semibold text-slate-900">{{ $pegawai->nama ?? $pengguna->nama }}</p>
        </div>
        <div>
            <p class="text-slate-500 mb-1">NIP</p>
            <p class="text-base font-semibold text-cyan-700 tabular-nums">{{ $pegawai->nomor_induk ?? '-' }}</p>
        </div>
        <div>
            <p class="text-slate-500 mb-1">Email</p>
            <p class="text-base text-slate-800">{{ $pengguna->email }}</p>
        </div>
        <div>
            <p class="text-slate-500 mb-1">Bagian Organisasi</p>
            <p class="text-base text-slate-800">{{ $pegawai->bagianOrganisasi->nama ?? '-' }}</p>
        </div>
        <div>
            <p class="text-slate-500 mb-1">Atasan Penyetuju</p>
            <p class="text-base text-slate-800">{{ $pegawai->atasan->nama ?? 'Admin HR' }}</p>
        </div>
        <div>
            <p class="text-slate-500 mb-1">Saldo Tersedia ({{ date('Y') }})</p>
            <p class="text-base font-bold text-orange-600 tabular-nums">{{ $saldo->saldo_tersedia ?? 12 }} Hari</p>
        </div>
    </div>

    <div class="md:col-span-5 bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-base font-bold text-slate-900 mb-4">Ubah Kata Sandi</h2>
        <form action="{{ route('profil.password') }}" method="POST" class="space-y-4 text-sm">
            @csrf @method('PATCH')
            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Kata Sandi Baru</label>
                <input type="password" name="password_baru" required minlength="6" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 outline-none focus:border-cyan-600">
            </div>
            <div>
                <label class="block font-semibold text-slate-700 mb-1.5">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_baru_confirmation" required minlength="6" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 outline-none focus:border-cyan-600">
            </div>
            <button type="submit" class="w-full py-2.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-semibold transition">
                Simpan Kata Sandi
            </button>
        </form>
    </div>
</div>
@endsection