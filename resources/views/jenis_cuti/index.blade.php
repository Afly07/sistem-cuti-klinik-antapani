@extends('layouts.app')
@section('title', 'Ketentuan & Jenis Cuti Tahunan')
@section('subtitle', 'Pedoman aturan bisnis pengajuan dan persetujuan cuti tahunan pada Klinik Utama Medika Antapani')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm">
        <span class="px-2.5 py-1 rounded-lg bg-teal-100 text-teal-800 text-xs font-bold">Aturan Kuota & Satuan</span>
        <h3 class="text-base font-bold text-slate-900 mt-3">1. Cuti Tahunan Reguler</h3>
        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
            Sesuai spesifikasi sistem informasi pengajuan cuti Klinik Utama Medika Antapani, jenis cuti yang dikelola pada sistem ini difokuskan secara khusus pada <strong>Cuti Tahunan</strong> dengan alokasi kuota dasar <strong>12 hari kerja per tahun</strong>.
        </p>
        <ul class="mt-3 space-y-1.5 text-xs text-slate-700">
            <li>• <strong>1 Pengajuan = 1 Tanggal Cuti</strong> (hari kerja aktif).</li>
            <li>• Tidak ada tabel terpisah untuk jenis cuti di luar cuti tahunan.</li>
            <li>• Pemotongan saldo sebesar 1 hari dilakukan otomatis saat disetujui akhir oleh Admin HR.</li>
        </ul>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm">
        <span class="px-2.5 py-1 rounded-lg bg-cyan-100 text-cyan-800 text-xs font-bold">Validasi Kalender</span>
        <h3 class="text-base font-bold text-slate-900 mt-3">2. Hari Kerja & Hari Libur</h3>
        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
            Sistem memvalidasi setiap tanggal pengajuan secara otomatis terhadap kalender operasional dan tabel <code>hari_libur</code>.
        </p>
        <ul class="mt-3 space-y-1.5 text-xs text-slate-700">
            <li>• Hari Sabtu dan Minggu otomatis ditolak oleh validator sistem.</li>
            <li>• Tanggal yang terdaftar pada <strong>Master Hari Libur</strong> (Nasional maupun Perusahaan/Klinik) tidak dapat diajukan.</li>
            <li>• Pegawai wajib memiliki <code>saldo_tersedia &ge; 1</code> pada tahun berjalan.</li>
        </ul>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-sm">
        <span class="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-800 text-xs font-bold">Otorisasi Berjenjang</span>
        <h3 class="text-base font-bold text-slate-900 mt-3">3. Alur Persetujuan 3 Aktor</h3>
        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
            Setiap pengajuan melalui siklus status terstruktur: <code>draf</code>, <code>menunggu_atasan</code>, <code>menunggu_hr</code>, <code>disetujui</code>, <code>ditolak</code>, dan <code>dibatalkan</code>.
        </p>
        <ul class="mt-3 space-y-1.5 text-xs text-slate-700">
            <li>• <strong>Staf / Karyawan</strong>: Diajukan ke Atasan Divisi &rarr; diteruskan ke Admin HR.</li>
            <li>• <strong>Atasan Divisi</strong>: Pengajuan pribadi langsung masuk ke antrean <code>menunggu_hr</code>.</li>
            <li>• <strong>Admin HR</strong>: Memberikan keputusan akhir dan mengelola master data klinik.</li>
        </ul>
    </div>
</div>
@endsection
