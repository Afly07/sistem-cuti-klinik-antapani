<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BagianOrganisasiController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PengajuanCutiController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SaldoCutiController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// Autentikasi Login & Logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Tombol Pintas Pindah Akun Cepat di Browser (Demo Role Switcher)
Route::get('/login-sebagai/{kodePeran}', function (string $kodePeran) {
    $pengguna = \App\Models\Pengguna::whereHas('peran', fn($q) => $q->where('kode', $kodePeran))
        ->where('aktif', true)
        ->firstOrFail();
    auth()->login($pengguna);
    return redirect()->route('dashboard')->with('success', 'Beralih akun cepat sebagai: ' . ($pengguna->pegawai->nama ?? $pengguna->nama));
})->name('login.cepat');

Route::middleware(['auth'])->group(function () {
    // Dashboard & Profil
    Route::get('/dashboard', [PengajuanCutiController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::patch('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');
    Route::get('/jenis-cuti', [PengajuanCutiController::class, 'jenisCuti'])->name('jenis-cuti.index');
    // Mengirim pengajuan yang berstatus draf
    Route::patch('/pengajuan-cuti/{pengajuan}/ajukan-draf', [PengajuanCutiController::class, 'ajukanDraf'])
        ->name('pengajuan.ajukanDraf');

    // Pembatalan cuti pasca-disetujui & refund saldo oleh Admin HR
    Route::patch('/pengajuan-cuti/{pengajuan}/batalkan-hr', [PengajuanCutiController::class, 'batalkanOlehHr'])
        ->name('pengajuan.batalkanOlehHr');
    // Modul Pengajuan Cuti (Staf & Atasan)
    Route::get('/pengajuan-cuti', [PengajuanCutiController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan-cuti/create', [PengajuanCutiController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan-cuti', [PengajuanCutiController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan-cuti/{pengajuan}', [PengajuanCutiController::class, 'show'])->name('pengajuan.show');
    Route::patch('/pengajuan-cuti/{pengajuan}/batalkan', [PengajuanCutiController::class, 'batalkan'])->name('pengajuan.batalkan');

    // Modul Persetujuan Tahap Atasan
    Route::get('/persetujuan-atasan', [PengajuanCutiController::class, 'halamanAtasan'])->name('persetujuan.atasan');
    Route::patch('/pengajuan-cuti/{pengajuan}/keputusan-atasan', [PengajuanCutiController::class, 'keputusanAtasan'])->name('pengajuan.keputusanAtasan');

    // Modul Persetujuan Akhir Admin HR & Laporan Cuti
    Route::get('/persetujuan-hr', [PengajuanCutiController::class, 'halamanHr'])->name('persetujuan.hr');
    Route::patch('/pengajuan-cuti/{pengajuan}/keputusan-hr', [PengajuanCutiController::class, 'keputusanHr'])->name('pengajuan.keputusanHr');
    Route::get('/laporan-cuti', [PengajuanCutiController::class, 'laporan'])->name('laporan.index');
    Route::get('/laporan-cuti/ekspor-csv', [PengajuanCutiController::class, 'eksporCsv'])->name('pengajuan.eksporCsv');

    // Modul Data Master (Admin HR)
    Route::resource('hari-libur', HariLiburController::class)->only(['index', 'store', 'destroy']);
    Route::patch('/hari-libur/{hari_libur}/toggle', [HariLiburController::class, 'toggleAktif'])->name('hari-libur.toggle');

    Route::get('/bagian-organisasi', [BagianOrganisasiController::class, 'index'])->name('bagian-organisasi.index');
    Route::post('/bagian-organisasi', [BagianOrganisasiController::class, 'store'])->name('bagian-organisasi.store');
    Route::patch('/bagian-organisasi/{bagianOrganisasi}', [BagianOrganisasiController::class, 'update'])->name('bagian-organisasi.update');
    Route::patch('/bagian-organisasi/{bagianOrganisasi}/toggle', [BagianOrganisasiController::class, 'toggleAktif'])->name('bagian-organisasi.toggle');

    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('pegawai.index');
    Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::patch('/pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('pegawai.update');
    Route::patch('/pegawai/{pegawai}/toggle', [PegawaiController::class, 'toggleAktif'])->name('pegawai.toggle');

    Route::get('/saldo-cuti', [SaldoCutiController::class, 'index'])->name('saldo-cuti.index');
    Route::patch('/saldo-cuti/{saldoCuti}', [SaldoCutiController::class, 'update'])->name('saldo-cuti.update');
    Route::post('/saldo-cuti/inisialisasi', [SaldoCutiController::class, 'inisialisasiTahun'])->name('saldo-cuti.inisialisasi');
});
