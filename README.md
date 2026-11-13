# SIMCUTI — Sistem Informasi Pengajuan & Persetujuan Cuti Tahunan (PWA)
**Studi Kasus: Klinik Utama Medika Antapani**

Aplikasi web berbasis pendekatan **Progressive Web App (PWA)** menggunakan **Laravel (PHP 8.3+)**, **Eloquent ORM**, **Policy Authorization**, **Blade & Tailwind CSS**, serta pengujian otomatis **PHPUnit**.

---

## 1. Kesesuaian dengan Spesifikasi Tugas (ERD, Use Case, Activity, & Navigasi)

### A. Skema Basis Data Relasional (8 Tabel Bahasa Indonesia)
1. `peran` (`id`, `kode`, `nama`, `aktif`) — `karyawan`, `atasan`, `admin_hr`
2. `pengguna` (`id`, `peran_id`, `nama`, `email`, `password`, `aktif`, `dibuat_pada`, `diperbarui_pada`)
3. `bagian_organisasi` (`id`, `kode`, `nama`, `aktif`)
4. `pegawai` (`id`, `pengguna_id`, `bagian_organisasi_id`, `atasan_id`, `nomor_induk`, `nama`, `tanggal_masuk`, `jatah_cuti`, `aktif`)
5. `saldo_cuti` (`id`, `pegawai_id`, `tahun`, `jatah_awal`, `terpakai`, `saldo_tersedia`, `catatan`)
6. `hari_libur` (`id`, `tanggal`, `nama`, `jenis` [`nasional`/`perusahaan`], `aktif`)
7. `pengajuan_cuti` (`id`, `nomor_pengajuan`, `pegawai_id`, `atasan_penyetuju_id`, `status` [`draf`, `menunggu_atasan`, `menunggu_hr`, `disetujui`, `ditolak`, `dibatalkan`], `alasan`, `tanggal_cuti`, `diajukan_pada`, `dibatalkan_pada`)
8. `persetujuan_cuti` (`id`, `pengajuan_cuti_id`, `pemberi_keputusan_id`, `tahap` [`atasan`/`admin_hr`], `keputusan` [`disetujui`/`ditolak`], `catatan`, `diputuskan_pada`)

### B. Kelengkapan Modul & Struktur Navigasi
- **Autentikasi & Demo Role Switcher**: Login, Logout, serta tombol pindah cepat akun (`Staf`, `Atasan`, `Admin HR`).
- **Dashboard Multi-Peran**: Kartu KPI saldo cuti (`Jatah Cuti Tahunan`, `Cuti Terpakai`, `Saldo Tersedia`), pengajuan terbaru, hari libur mendatang, dan tabel **Monitoring Sisa Saldo Cuti Staf**.
- **Pengajuan Cuti (`1 Pengajuan = 1 Hari Cuti Tahunan`)**: Validasi otomatis menolak hari Sabtu/Minggu, menolak tanggal pada `hari_libur`, menolak duplikasi tanggal aktif, dan memeriksa `saldo_tersedia`. Mendukung penyimpanan sebagai `draf` maupun pengajuan langsung.
- **Detail Pengajuan & Pelacakan Timeline**: Visualisasi stepper 4 tahap dan riwayat audit `persetujuan_cuti`.
- **Persetujuan Berjenjang**:
  - **Tahap Atasan (`/persetujuan-atasan`)**: Antrean pengajuan bawahan + tabel Riwayat Keputusan Atasan.
  - **Tahap Admin HR (`/persetujuan-hr`)**: Antrean keputusan akhir + pemotongan otomatis `saldo_cuti` dalam transaksi database (`lockForUpdate`) + tabel Riwayat Keputusan HR.
- **Manajemen Data Master & Laporan (Admin HR)**:
  - Master Bagian Organisasi (`/bagian-organisasi`)
  - Master Pengguna & Pegawai (`/pegawai`)
  - Kelola Saldo Cuti Tahunan (`/saldo-cuti`)
  - Master Hari Libur (`/hari-libur`)
  - Laporan Cuti, Filter, Cetak, & Ekspor CSV (`/laporan-cuti` & `/laporan-cuti/ekspor-csv`)
  - Profil & Saldo Cuti (`/profil`) serta Informasi Kebijakan Cuti Tahunan (`/jenis-cuti`)

---

## 2. Akun Demo Siap Pakai

| Peran | Nama Pegawai | Email | Kata Sandi |
| :--- | :--- | :--- | :--- |
| **Staf / Karyawan** | Budi Santoso, S.Farm. | `staf@klinikantapani.id` | `password` |
| **Atasan Divisi** | dr. Andi Pratama, Sp.PD | `atasan@klinikantapani.id` | `password` |
| **Admin HR** | Siti Rahma, S.Psi. | `hr@klinikantapani.id` | `password` |

---

## 3. Cara Menjalankan Proyek

```bash
# 1. Migrasi dan isi data awal (Seeder)
php artisan migrate:fresh --seed

# 2. Bersihkan cache view & konfigurasi
php artisan optimize:clear

# 3. Jalankan server pengembangan
php artisan serve
```

Untuk menjalankan pengujian otomatis **PHPUnit**:
```bash
php artisan test
```
