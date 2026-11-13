<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tahun = (int) date('Y');

        // 1. Tabel peran (karyawan, atasan, admin_hr)
        $peranKaryawan = DB::table('peran')->insertGetId(['kode' => 'karyawan', 'nama' => 'Staf / Karyawan', 'aktif' => true]);
        $peranAtasan   = DB::table('peran')->insertGetId(['kode' => 'atasan', 'nama' => 'Atasan Divisi', 'aktif' => true]);
        $peranHr       = DB::table('peran')->insertGetId(['kode' => 'admin_hr', 'nama' => 'Admin HR', 'aktif' => true]);

        // 2. Tabel bagian_organisasi (Klinik Utama Medika Antapani)
        $bagianFarmasi = DB::table('bagian_organisasi')->insertGetId(['kode' => 'FRM', 'nama' => 'Instalasi Farmasi', 'aktif' => true]);
        $bagianPoli    = DB::table('bagian_organisasi')->insertGetId(['kode' => 'PLY', 'nama' => 'Pelayanan Medis & Poliklinik', 'aktif' => true]);
        $bagianLab     = DB::table('bagian_organisasi')->insertGetId(['kode' => 'LAB', 'nama' => 'Laboratorium & Penunjang Medis', 'aktif' => true]);
        $bagianHr      = DB::table('bagian_organisasi')->insertGetId(['kode' => 'HRD', 'nama' => 'Sumber Daya Manusia & Umum', 'aktif' => true]);

        // 3. Tabel pengguna
        $userHrId = DB::table('pengguna')->insertGetId([
            'peran_id' => $peranHr, 'nama' => 'Siti Rahma, S.Psi.', 'email' => 'hr@klinikantapani.id',
            'password' => Hash::make('password'), 'aktif' => true,
        ]);
        $userAtasanId = DB::table('pengguna')->insertGetId([
            'peran_id' => $peranAtasan, 'nama' => 'dr. Andi Pratama, Sp.PD', 'email' => 'atasan@klinikantapani.id',
            'password' => Hash::make('password'), 'aktif' => true,
        ]);
        $userAtasanPoliId = DB::table('pengguna')->insertGetId([
            'peran_id' => $peranAtasan, 'nama' => 'drg. Maya Kartika', 'email' => 'maya@klinikantapani.id',
            'password' => Hash::make('password'), 'aktif' => true,
        ]);
        $userStafId = DB::table('pengguna')->insertGetId([
            'peran_id' => $peranKaryawan, 'nama' => 'Budi Santoso, S.Farm.', 'email' => 'staf@klinikantapani.id',
            'password' => Hash::make('password'), 'aktif' => true,
        ]);
        $userStaf2Id = DB::table('pengguna')->insertGetId([
            'peran_id' => $peranKaryawan, 'nama' => 'Rina Wulandari, A.Md.Farm.', 'email' => 'rina@klinikantapani.id',
            'password' => Hash::make('password'), 'aktif' => true,
        ]);
        $userStaf3Id = DB::table('pengguna')->insertGetId([
            'peran_id' => $peranKaryawan, 'nama' => 'Dedi Kurniawan, S.Kep., Ners', 'email' => 'dedi@klinikantapani.id',
            'password' => Hash::make('password'), 'aktif' => true,
        ]);

        // 4. Tabel pegawai (atasan_id self-reference)
        $pegawaiHrId = DB::table('pegawai')->insertGetId([
            'pengguna_id' => $userHrId, 'bagian_organisasi_id' => $bagianHr, 'atasan_id' => null,
            'nomor_induk' => 'KAR-001', 'nama' => 'Siti Rahma, S.Psi.', 'tanggal_masuk' => '2019-01-10', 'jatah_cuti' => 12, 'aktif' => true,
        ]);
        $pegawaiAtasanId = DB::table('pegawai')->insertGetId([
            'pengguna_id' => $userAtasanId, 'bagian_organisasi_id' => $bagianFarmasi, 'atasan_id' => null,
            'nomor_induk' => 'KAR-002', 'nama' => 'dr. Andi Pratama, Sp.PD', 'tanggal_masuk' => '2020-03-01', 'jatah_cuti' => 12, 'aktif' => true,
        ]);
        $pegawaiAtasanPoliId = DB::table('pegawai')->insertGetId([
            'pengguna_id' => $userAtasanPoliId, 'bagian_organisasi_id' => $bagianPoli, 'atasan_id' => null,
            'nomor_induk' => 'KAR-003', 'nama' => 'drg. Maya Kartika', 'tanggal_masuk' => '2020-08-12', 'jatah_cuti' => 12, 'aktif' => true,
        ]);
        $pegawaiStafId = DB::table('pegawai')->insertGetId([
            'pengguna_id' => $userStafId, 'bagian_organisasi_id' => $bagianFarmasi, 'atasan_id' => $pegawaiAtasanId,
            'nomor_induk' => 'KAR-004', 'nama' => 'Budi Santoso, S.Farm.', 'tanggal_masuk' => '2022-07-15', 'jatah_cuti' => 12, 'aktif' => true,
        ]);
        $pegawaiStaf2Id = DB::table('pegawai')->insertGetId([
            'pengguna_id' => $userStaf2Id, 'bagian_organisasi_id' => $bagianFarmasi, 'atasan_id' => $pegawaiAtasanId,
            'nomor_induk' => 'KAR-005', 'nama' => 'Rina Wulandari, A.Md.Farm.', 'tanggal_masuk' => '2023-02-01', 'jatah_cuti' => 12, 'aktif' => true,
        ]);
        $pegawaiStaf3Id = DB::table('pegawai')->insertGetId([
            'pengguna_id' => $userStaf3Id, 'bagian_organisasi_id' => $bagianPoli, 'atasan_id' => $pegawaiAtasanPoliId,
            'nomor_induk' => 'KAR-006', 'nama' => 'Dedi Kurniawan, S.Kep., Ners', 'tanggal_masuk' => '2021-11-20', 'jatah_cuti' => 12, 'aktif' => true,
        ]);

        // 5. Tabel saldo_cuti
        DB::table('saldo_cuti')->insert([
            ['pegawai_id' => $pegawaiHrId, 'tahun' => $tahun, 'jatah_awal' => 12, 'terpakai' => 0, 'saldo_tersedia' => 12, 'catatan' => 'Jatah cuti tahunan reguler'],
            ['pegawai_id' => $pegawaiAtasanId, 'tahun' => $tahun, 'jatah_awal' => 12, 'terpakai' => 0, 'saldo_tersedia' => 12, 'catatan' => 'Jatah cuti tahunan reguler'],
            ['pegawai_id' => $pegawaiAtasanPoliId, 'tahun' => $tahun, 'jatah_awal' => 12, 'terpakai' => 0, 'saldo_tersedia' => 12, 'catatan' => 'Jatah cuti tahunan reguler'],
            ['pegawai_id' => $pegawaiStafId, 'tahun' => $tahun, 'jatah_awal' => 12, 'terpakai' => 1, 'saldo_tersedia' => 11, 'catatan' => 'Jatah cuti tahunan reguler'],
            ['pegawai_id' => $pegawaiStaf2Id, 'tahun' => $tahun, 'jatah_awal' => 12, 'terpakai' => 0, 'saldo_tersedia' => 12, 'catatan' => 'Jatah cuti tahunan reguler'],
            ['pegawai_id' => $pegawaiStaf3Id, 'tahun' => $tahun, 'jatah_awal' => 12, 'terpakai' => 0, 'saldo_tersedia' => 12, 'catatan' => 'Jatah cuti tahunan reguler'],
        ]);

        // 6. Tabel hari_libur
        DB::table('hari_libur')->insert([
            ['tanggal' => $tahun . '-01-01', 'nama' => 'Tahun Baru Masehi', 'jenis' => 'nasional', 'aktif' => true],
            ['tanggal' => $tahun . '-08-17', 'nama' => 'Hari Kemerdekaan Republik Indonesia', 'jenis' => 'nasional', 'aktif' => true],
            ['tanggal' => $tahun . '-11-18', 'nama' => 'HUT Klinik Utama Medika Antapani', 'jenis' => 'perusahaan', 'aktif' => true],
            ['tanggal' => $tahun . '-12-25', 'nama' => 'Hari Raya Natal', 'jenis' => 'nasional', 'aktif' => true],
        ]);

        // 7. Contoh Data Pengajuan & Persetujuan Cuti
        $cutiDisetujuiId = DB::table('pengajuan_cuti')->insertGetId([
            'nomor_pengajuan'     => 'CT-' . $tahun . '1014-A101',
            'pegawai_id'          => $pegawaiStafId,
            'atasan_penyetuju_id' => $pegawaiAtasanId,
            'status'              => 'disetujui',
            'alasan'              => 'Mendampingi keluarga kontrol kesehatan di RSUP Hasan Sadikin',
            'tanggal_cuti'        => $tahun . '-10-15',
            'diajukan_pada'       => $tahun . '-10-10 08:30:00',
        ]);

        DB::table('persetujuan_cuti')->insert([
            [
                'pengajuan_cuti_id'    => $cutiDisetujuiId,
                'pemberi_keputusan_id' => $pegawaiAtasanId,
                'tahap'                => 'atasan',
                'keputusan'            => 'disetujui',
                'catatan'              => 'Jadwal shift apotek sudah didelegasikan ke Rina.',
                'diputuskan_pada'      => $tahun . '-10-10 11:15:00',
            ],
            [
                'pengajuan_cuti_id'    => $cutiDisetujuiId,
                'pemberi_keputusan_id' => $pegawaiHrId,
                'tahap'                => 'admin_hr',
                'keputusan'            => 'disetujui',
                'catatan'              => 'Kuota cuti tahunan mencukupi, disetujui HRD.',
                'diputuskan_pada'      => $tahun . '-10-10 14:20:00',
            ],
        ]);

        // Antrean menunggu_atasan
        DB::table('pengajuan_cuti')->insert([
            'nomor_pengajuan'     => 'CT-' . $tahun . '1113-B204',
            'pegawai_id'          => $pegawaiStaf2Id,
            'atasan_penyetuju_id' => $pegawaiAtasanId,
            'status'              => 'menunggu_atasan',
            'alasan'              => 'Menghadiri wisuda adik kandung di Bandung',
            'tanggal_cuti'        => $tahun . '-11-24',
            'diajukan_pada'       => now(),
        ]);

        // Antrean menunggu_hr (Pengajuan pribadi Atasan langsung ke HR)
        DB::table('pengajuan_cuti')->insert([
            'nomor_pengajuan'     => 'CT-' . $tahun . '1113-C309',
            'pegawai_id'          => $pegawaiAtasanId,
            'atasan_penyetuju_id' => null,
            'status'              => 'menunggu_hr',
            'alasan'              => 'Menghadiri Simposium Nasional Penyakit Dalam',
            'tanggal_cuti'        => $tahun . '-11-26',
            'diajukan_pada'       => now(),
        ]);
    }
}
