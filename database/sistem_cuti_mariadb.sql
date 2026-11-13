-- =========================================================================
-- DATABASE DUMP: SISTEM INFORMASI PENGAJUAN CUTI TAHUNAN (PWA)
-- OBJEK KASUS  : KLINIK UTAMA MEDIKA ANTAPANI
-- DBMS         : MariaDB / MySQL (Sesuai ERD 8 Tabel Bahasa Indonesia)
-- =========================================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `persetujuan_cuti`;
DROP TABLE IF EXISTS `pengajuan_cuti`;
DROP TABLE IF EXISTS `hari_libur`;
DROP TABLE IF EXISTS `saldo_cuti`;
DROP TABLE IF EXISTS `pegawai`;
DROP TABLE IF EXISTS `bagian_organisasi`;
DROP TABLE IF EXISTS `pengguna`;
DROP TABLE IF EXISTS `peran`;

CREATE TABLE `peran` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(30) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `peran_kode_unique` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pengguna` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `peran_id` bigint(20) unsigned NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `diperbarui_pada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengguna_email_unique` (`email`),
  KEY `pengguna_peran_id_foreign` (`peran_id`),
  CONSTRAINT `pengguna_peran_id_foreign` FOREIGN KEY (`peran_id`) REFERENCES `peran` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `bagian_organisasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bagian_organisasi_kode_unique` (`kode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pegawai` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pengguna_id` bigint(20) unsigned NOT NULL,
  `bagian_organisasi_id` bigint(20) unsigned NOT NULL,
  `atasan_id` bigint(20) unsigned DEFAULT NULL,
  `nomor_induk` varchar(30) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `jatah_cuti` int(10) unsigned NOT NULL DEFAULT 12,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pegawai_pengguna_id_unique` (`pengguna_id`),
  UNIQUE KEY `pegawai_nomor_induk_unique` (`nomor_induk`),
  KEY `pegawai_bagian_organisasi_id_foreign` (`bagian_organisasi_id`),
  KEY `pegawai_atasan_id_foreign` (`atasan_id`),
  CONSTRAINT `pegawai_atasan_id_foreign` FOREIGN KEY (`atasan_id`) REFERENCES `pegawai` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pegawai_bagian_organisasi_id_foreign` FOREIGN KEY (`bagian_organisasi_id`) REFERENCES `bagian_organisasi` (`id`),
  CONSTRAINT `pegawai_pengguna_id_foreign` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `saldo_cuti` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pegawai_id` bigint(20) unsigned NOT NULL,
  `tahun` year(4) NOT NULL,
  `jatah_awal` int(10) unsigned NOT NULL DEFAULT 12,
  `terpakai` int(10) unsigned NOT NULL DEFAULT 0,
  `saldo_tersedia` int(10) unsigned NOT NULL DEFAULT 12,
  `catatan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `saldo_cuti_pegawai_id_tahun_unique` (`pegawai_id`,`tahun`),
  CONSTRAINT `saldo_cuti_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `hari_libur` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis` enum('nasional','perusahaan') NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hari_libur_tanggal_unique` (`tanggal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pengajuan_cuti` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nomor_pengajuan` varchar(30) NOT NULL,
  `pegawai_id` bigint(20) unsigned NOT NULL,
  `atasan_penyetuju_id` bigint(20) unsigned DEFAULT NULL,
  `status` enum('draf','menunggu_atasan','menunggu_hr','disetujui','ditolak','dibatalkan') NOT NULL,
  `alasan` text NOT NULL,
  `tanggal_cuti` date NOT NULL,
  `diajukan_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  `dibatalkan_pada` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengajuan_cuti_nomor_pengajuan_unique` (`nomor_pengajuan`),
  KEY `pengajuan_cuti_pegawai_id_foreign` (`pegawai_id`),
  KEY `pengajuan_cuti_atasan_penyetuju_id_foreign` (`atasan_penyetuju_id`),
  CONSTRAINT `pengajuan_cuti_atasan_penyetuju_id_foreign` FOREIGN KEY (`atasan_penyetuju_id`) REFERENCES `pegawai` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pengajuan_cuti_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `persetujuan_cuti` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pengajuan_cuti_id` bigint(20) unsigned NOT NULL,
  `pemberi_keputusan_id` bigint(20) unsigned NOT NULL,
  `tahap` enum('atasan','admin_hr') NOT NULL,
  `keputusan` enum('disetujui','ditolak') NOT NULL,
  `catatan` text DEFAULT NULL,
  `diputuskan_pada` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `persetujuan_cuti_pengajuan_cuti_id_foreign` (`pengajuan_cuti_id`),
  KEY `persetujuan_cuti_pemberi_keputusan_id_foreign` (`pemberi_keputusan_id`),
  CONSTRAINT `persetujuan_cuti_pemberi_keputusan_id_foreign` FOREIGN KEY (`pemberi_keputusan_id`) REFERENCES `pegawai` (`id`),
  CONSTRAINT `persetujuan_cuti_pengajuan_cuti_id_foreign` FOREIGN KEY (`pengajuan_cuti_id`) REFERENCES `pengajuan_cuti` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
