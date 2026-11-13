@extends('layouts.app')
@section('title', 'Kelola Saldo Cuti Tahunan')
@section('subtitle', 'Atur kuota awal, pantau pemakaian, dan sesuaikan saldo cuti tahunan seluruh pegawai klinik')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm">
    <div>
        <h4 class="text-sm font-bold text-slate-900">Manajemen Kuota Saldo Cuti Tahun {{ $tahun }}</h4>
        <p class="text-xs text-slate-500">Perubahan jatah awal otomatis menyesuaikan sisa saldo tersedia (`jatah_awal - terpakai`)</p>
    </div>
    <div class="flex items-center gap-2">
        <form action="{{ route('saldo-cuti.inisialisasi') }}" method="POST">
            @csrf
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <button type="submit" class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold shadow-sm transition">
                + Sinkronkan Saldo Seluruh Pegawai ({{ $tahun }})
            </button>
        </form>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-slate-900 text-white text-xs uppercase">
                    <th class="py-3.5 px-4 font-bold">NIP & Pegawai</th>
                    <th class="py-3.5 px-4 font-bold">Bagian</th>
                    <th class="py-3.5 px-4 font-bold">Tahun</th>
                    <th class="py-3.5 px-4 font-bold">Terpakai</th>
                    <th class="py-3.5 px-4 font-bold">Saldo Tersedia</th>
                    <th class="py-3.5 px-4 font-bold">Penyesuaian Jatah Awal & Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($daftarSaldo as $s)
                <tr class="hover:bg-slate-50/80">
                    <td class="py-3.5 px-4">
                        <p class="font-bold text-slate-900 text-xs">{{ $s->pegawai->nama ?? '-' }}</p>
                        <p class="font-mono text-[11px] text-slate-500">{{ $s->pegawai->nomor_induk ?? '-' }}</p>
                    </td>
                    <td class="py-3.5 px-4 text-xs text-slate-600">{{ $s->pegawai->bagianOrganisasi->nama ?? '-' }}</td>
                    <td class="py-3.5 px-4 text-xs font-bold text-slate-700">{{ $s->tahun }}</td>
                    <td class="py-3.5 px-4 text-xs font-bold text-amber-700">{{ $s->terpakai }} Hari</td>
                    <td class="py-3.5 px-4 text-xs font-extrabold text-teal-700">{{ $s->saldo_tersedia }} Hari</td>
                    <td class="py-3.5 px-4">
                        <form action="{{ route('saldo-cuti.update', $s->id) }}" method="POST" class="flex flex-wrap sm:flex-nowrap items-center gap-2">
                            @csrf @method('PATCH')
                            <input type="number" name="jatah_awal" value="{{ $s->jatah_awal }}" min="0" max="30" class="w-20 rounded-xl border border-slate-300 px-2.5 py-1.5 text-xs font-bold text-center">
                            <input type="text" name="catatan" value="{{ $s->catatan }}" placeholder="Catatan kuota..." class="w-48 rounded-xl border border-slate-300 px-3 py-1.5 text-xs">
                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-teal-600 text-white text-xs font-bold transition">Simpan</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center text-xs text-slate-400">Belum ada data saldo cuti untuk tahun ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
