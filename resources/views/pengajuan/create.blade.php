@extends('layouts.app')
@section('title', 'Ajukan Cuti')

@section('content')
<div class="max-w-xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Formulir Pengajuan Cuti</h1>
        <p class="text-base text-slate-600 mt-1">
            Saldo tersedia: <strong class="text-cyan-700 tabular-nums">{{ $saldo->saldo_tersedia ?? 12 }} Hari</strong>
        </p>
    </div>

    <form action="{{ route('pengajuan.store') }}" method="POST" class="bg-white rounded-xl border border-slate-200 p-6 space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-semibold text-slate-800 mb-2">Tanggal Cuti (Hari Kerja Senin–Jumat)</label>
            <input type="date" name="tanggal_cuti" value="{{ old('tanggal_cuti') }}" min="{{ date('Y-m-d') }}" required
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-base text-slate-900 focus:border-cyan-600 focus:ring-2 focus:ring-cyan-500/20 outline-none">
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-800 mb-2">Alasan Cuti</label>
            <textarea name="alasan" rows="4" required placeholder="Tuliskan keperluan cuti..."
                class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-base text-slate-900 focus:border-cyan-600 focus:ring-2 focus:ring-cyan-500/20 outline-none">{{ old('alasan') }}</textarea>
        </div>

        <div class="pt-2 flex items-center gap-3">
            <button type="submit" name="simpan_sebagai" value="ajukan" class="px-5 py-2.5 rounded-lg bg-orange-600 hover:bg-orange-700 text-white text-sm font-semibold transition">
                Kirim Pengajuan
            </button>
            <button type="submit" name="simpan_sebagai" value="draf" class="px-4 py-2.5 rounded-lg border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-semibold transition">
                Simpan Draf
            </button>
            <a href="{{ route('pengajuan.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900 ml-auto">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection