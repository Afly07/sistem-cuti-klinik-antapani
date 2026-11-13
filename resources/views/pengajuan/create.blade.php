@extends('layouts.app')
@section('title', 'Ajukan Cuti Tahunan')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl border border-slate-200 p-6">
    <div class="mb-5 pb-4 border-b border-slate-200 flex items-center justify-between">
        <h1 class="text-lg font-bold text-slate-900">Formulir Pengajuan Cuti</h1>
        <span class="text-sm font-medium text-teal-700">Sisa: {{ $saldo->saldo_tersedia ?? 12 }} Hari</span>
    </div>

    <form action="{{ route('pengajuan.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Cuti (Senin–Jumat)</label>
            <input type="date" name="tanggal_cuti" value="{{ old('tanggal_cuti') }}" min="{{ date('Y-m-d') }}" required
                class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-600 outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Alasan Cuti</label>
            <textarea name="alasan" rows="4" required placeholder="Tuliskan keperluan cuti Anda..."
                class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-600 outline-none">{{ old('alasan') }}</textarea>
        </div>

        <div class="pt-2 flex items-center gap-3">
            <button type="submit" name="simpan_sebagai" value="ajukan" class="px-4 py-2 rounded-lg bg-teal-700 hover:bg-teal-800 text-white text-sm font-semibold">
                Kirim Pengajuan
            </button>
            <button type="submit" name="simpan_sebagai" value="draf" class="px-4 py-2 rounded-lg border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium">
                Simpan Draf
            </button>
            <a href="{{ route('pengajuan.index') }}" class="text-sm text-slate-500 hover:text-slate-800 ml-auto">Batal</a>
        </div>
    </form>
</div>
@endsection