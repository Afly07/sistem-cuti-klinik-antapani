@extends('layouts.app')
@section('title', 'Formulir Pengajuan Cuti Tahunan')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden max-w-2xl">
    <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between">
        <h3 class="text-sm font-bold">Formulir Pengajuan Cuti Tahunan (1 Pengajuan = 1 Hari Kerja)</h3>
        <span class="text-xs font-bold text-teal-300">Sisa Saldo: {{ $saldo->saldo_tersedia ?? 12 }} Hari</span>
    </div>
    <form action="{{ route('pengajuan.store') }}" method="POST" class="p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Tanggal Cuti (Senin–Jumat)</label>
            <input type="date" name="tanggal_cuti" value="{{ old('tanggal_cuti') }}" required class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1.5">Alasan Cuti</label>
            <textarea name="alasan" rows="3" required class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm">{{ old('alasan') }}</textarea>
        </div>
        <div class="flex items-center gap-3 pt-2">
            <button type="submit" name="simpan_sebagai" value="ajukan" class="px-5 py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold">Kirim Pengajuan</button>
            <button type="submit" name="simpan_sebagai" value="draf" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold border border-slate-300">Simpan Sebagai Draf</button>
            <a href="{{ route('pengajuan.index') }}" class="text-xs font-semibold text-slate-500">Batal</a>
        </div>
    </form>
</div>
@endsection