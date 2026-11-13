<?php

namespace Tests\Feature;

use App\Models\BagianOrganisasi;
use App\Models\HariLibur;
use App\Models\Pegawai;
use App\Models\PengajuanCuti;
use App\Models\Pengguna;
use App\Models\Peran;
use App\Models\SaldoCuti;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlurPengajuanCutiTest extends TestCase
{
    use RefreshDatabase;

    protected Pengguna $userStaf;
    protected Pengguna $userAtasan;
    protected Pengguna $userHr;
    protected Pegawai $pegawaiStaf;
    protected Pegawai $pegawaiAtasan;
    protected Pegawai $pegawaiHr;
    protected BagianOrganisasi $bagian;
    protected Peran $peranStaf;

    protected function setUp(): void
    {
        parent::setUp();

        // Kunci waktu pengujian pada Jumat, 13 November 2026 agar deterministik
        $this->travelTo(Carbon::parse('2026-11-13 09:00:00'));

        $this->peranStaf = Peran::create(['kode' => 'karyawan', 'nama' => 'Staf / Karyawan', 'aktif' => true]);
        $peranAtasan     = Peran::create(['kode' => 'atasan', 'nama' => 'Atasan Divisi', 'aktif' => true]);
        $peranHr         = Peran::create(['kode' => 'admin_hr', 'nama' => 'Admin HR', 'aktif' => true]);

        $this->bagian = BagianOrganisasi::create(['kode' => 'FRM', 'nama' => 'Instalasi Farmasi', 'aktif' => true]);

        $this->userAtasan = Pengguna::create([
            'peran_id' => $peranAtasan->id, 'nama' => 'dr. Andi', 'email' => 'atasan@klinik.id', 'password' => bcrypt('secret'), 'aktif' => true
        ]);
        $this->pegawaiAtasan = Pegawai::create([
            'pengguna_id' => $this->userAtasan->id, 'bagian_organisasi_id' => $this->bagian->id,
            'nomor_induk' => 'NIP-001', 'nama' => 'dr. Andi', 'tanggal_masuk' => '2020-01-10', 'jatah_cuti' => 12, 'aktif' => true
        ]);
        SaldoCuti::create(['pegawai_id' => $this->pegawaiAtasan->id, 'tahun' => 2026, 'jatah_awal' => 12, 'terpakai' => 0, 'saldo_tersedia' => 12]);

        $this->userStaf = Pengguna::create([
            'peran_id' => $this->peranStaf->id, 'nama' => 'Budi Santoso', 'email' => 'staf@klinik.id', 'password' => bcrypt('secret'), 'aktif' => true
        ]);
        $this->pegawaiStaf = Pegawai::create([
            'pengguna_id' => $this->userStaf->id, 'bagian_organisasi_id' => $this->bagian->id, 'atasan_id' => $this->pegawaiAtasan->id,
            'nomor_induk' => 'NIP-002', 'nama' => 'Budi Santoso', 'tanggal_masuk' => '2022-03-15', 'jatah_cuti' => 12, 'aktif' => true
        ]);
        SaldoCuti::create(['pegawai_id' => $this->pegawaiStaf->id, 'tahun' => 2026, 'jatah_awal' => 12, 'terpakai' => 0, 'saldo_tersedia' => 12]);

        $this->userHr = Pengguna::create([
            'peran_id' => $peranHr->id, 'nama' => 'Siti HRD', 'email' => 'hr@klinik.id', 'password' => bcrypt('secret'), 'aktif' => true
        ]);
        $this->pegawaiHr = Pegawai::create([
            'pengguna_id' => $this->userHr->id, 'bagian_organisasi_id' => $this->bagian->id,
            'nomor_induk' => 'NIP-003', 'nama' => 'Siti HRD', 'tanggal_masuk' => '2019-06-01', 'jatah_cuti' => 12, 'aktif' => true
        ]);
        SaldoCuti::create(['pegawai_id' => $this->pegawaiHr->id, 'tahun' => 2026, 'jatah_awal' => 12, 'terpakai' => 0, 'saldo_tersedia' => 12]);
    }

    public function test_pengajuan_cuti_ditolak_jika_jatuh_pada_hari_libur(): void
    {
        HariLibur::create(['tanggal' => '2026-12-25', 'nama' => 'Hari Raya Natal', 'jenis' => 'nasional', 'aktif' => true]);

        $response = $this->actingAs($this->userStaf)->post(route('pengajuan.store'), [
            'tanggal_cuti' => '2026-12-25',
            'alasan'       => 'Liburan keluarga',
        ]);

        $response->assertSessionHasErrors('tanggal_cuti');
        $this->assertDatabaseCount('pengajuan_cuti', 0);
    }

    public function test_alur_lengkap_pengajuan_staf_hingga_disetujui_hr_dan_potong_saldo(): void
    {
        // 1. Staf mengajukan cuti -> status: menunggu_atasan
        $this->actingAs($this->userStaf)->post(route('pengajuan.store'), [
            'tanggal_cuti' => '2026-11-20',
            'alasan'       => 'Acara keluarga',
        ]);

        $pengajuan = PengajuanCuti::first();
        $this->assertNotNull($pengajuan);
        $this->assertEquals('menunggu_atasan', $pengajuan->status);

        // 2. Atasan menyetujui -> status berubah menjadi: menunggu_hr
        $this->actingAs($this->userAtasan)->patch(route('pengajuan.keputusanAtasan', $pengajuan->id), [
            'keputusan' => 'disetujui',
            'catatan'   => 'Disetujui oleh Ka. Instalasi',
        ]);
        $this->assertEquals('menunggu_hr', $pengajuan->fresh()->status);

        // 3. Admin HR menyetujui -> status: disetujui & saldo_tersedia berkurang dari 12 menjadi 11
        $this->actingAs($this->userHr)->patch(route('pengajuan.keputusanHr', $pengajuan->id), [
            'keputusan' => 'disetujui',
            'catatan'   => 'Persetujuan akhir HRD',
        ]);

        $this->assertEquals('disetujui', $pengajuan->fresh()->status);
        $this->assertDatabaseHas('saldo_cuti', [
            'pegawai_id'     => $this->pegawaiStaf->id,
            'terpakai'       => 1,
            'saldo_tersedia' => 11,
        ]);
    }

    public function test_pengajuan_pribadi_atasan_langsung_menuju_status_menunggu_hr(): void
    {
        $this->actingAs($this->userAtasan)->post(route('pengajuan.store'), [
            'tanggal_cuti' => '2026-11-26',
            'alasan'       => 'Seminar medis luar kota',
        ]);

        $pengajuan = PengajuanCuti::first();
        $this->assertNotNull($pengajuan);
        $this->assertEquals('menunggu_hr', $pengajuan->status);
    }

    public function test_staf_tidak_bisa_menyetujui_pengajuan_cuti_orang_lain(): void
    {
        $pengajuan = PengajuanCuti::create([
            'nomor_pengajuan'     => 'CT-2026-0001',
            'pegawai_id'          => $this->pegawaiStaf->id,
            'atasan_penyetuju_id' => $this->pegawaiAtasan->id,
            'status'              => 'menunggu_atasan',
            'alasan'              => 'Keperluan pribadi',
            'tanggal_cuti'        => '2026-11-23',
        ]);

        $response = $this->actingAs($this->userStaf)->patch(route('pengajuan.keputusanAtasan', $pengajuan->id), [
            'keputusan' => 'disetujui',
        ]);

        $response->assertStatus(403);
    }

    public function test_pengajuan_cuti_ditolak_jika_memilih_hari_sabtu_atau_minggu(): void
    {
        // 2026-11-21 adalah hari Sabtu
        $responseSabtu = $this->actingAs($this->userStaf)->post(route('pengajuan.store'), [
            'tanggal_cuti' => '2026-11-21',
            'alasan'       => 'Liburan akhir pekan',
        ]);

        $responseSabtu->assertSessionHasErrors('tanggal_cuti');
        $this->assertDatabaseCount('pengajuan_cuti', 0);
    }

    public function test_pimpinan_dapat_melihat_sisa_saldo_cuti_staf_bawahannya(): void
    {
        $response = $this->actingAs($this->userAtasan)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Monitoring Sisa Saldo Cuti Staf');
        $response->assertSee('Budi Santoso');
        $response->assertSee('12 Hari');
    }

    public function test_staf_dapat_menyimpan_draf_mengirim_dan_membatalkan_pengajuan(): void
    {
        $this->actingAs($this->userStaf)->post(route('pengajuan.store'), [
            'tanggal_cuti'   => '2026-11-24',
            'alasan'         => 'Keperluan administrasi keluarga',
            'simpan_sebagai' => 'draf',
        ]);

        $pengajuan = PengajuanCuti::first();
        $this->assertEquals('draf', $pengajuan->status);

        // Kirim draf menjadi menunggu_atasan
        $this->actingAs($this->userStaf)->patch(route('pengajuan.ajukanDraf', $pengajuan->id));
        $this->assertEquals('menunggu_atasan', $pengajuan->fresh()->status);

        // Batalkan pengajuan
        $this->actingAs($this->userStaf)->patch(route('pengajuan.batalkan', $pengajuan->id));
        $this->assertEquals('dibatalkan', $pengajuan->fresh()->status);
        $this->assertNotNull($pengajuan->fresh()->dibatalkan_pada);
    }

    public function test_admin_hr_dapat_mengelola_data_master_dan_laporan_cuti(): void
    {
        // Tambah Bagian Organisasi
        $this->actingAs($this->userHr)->post(route('bagian-organisasi.store'), [
            'kode' => 'IGD',
            'nama' => 'Instalasi Gawat Darurat',
        ])->assertRedirect();
        $this->assertDatabaseHas('bagian_organisasi', ['kode' => 'IGD']);

        // Tambah Pegawai Baru
        $this->actingAs($this->userHr)->post(route('pegawai.store'), [
            'nomor_induk'          => 'NIP-010',
            'nama'                 => 'Rina Farmasi',
            'email'                => 'rina@klinik.id',
            'password'             => 'secret123',
            'peran_id'             => $this->peranStaf->id,
            'bagian_organisasi_id' => $this->bagian->id,
            'atasan_id'            => $this->pegawaiAtasan->id,
            'tanggal_masuk'        => '2024-01-15',
            'jatah_cuti'           => 12,
        ])->assertRedirect();
        $this->assertDatabaseHas('pegawai', ['nomor_induk' => 'NIP-010']);

        // Halaman Laporan & Ekspor CSV
        $this->actingAs($this->userHr)->get(route('laporan.index'))->assertStatus(200);
        $this->actingAs($this->userHr)->get(route('pengajuan.eksporCsv'))->assertStatus(200);
    }

    public function test_pengajuan_ditolak_jika_sisa_saldo_habis_terkunci_di_antrean_menggantung(): void
    {
    // Set sisa saldo Budi hanya tinggal 1 hari
    SaldoCuti::where('pegawai_id', $this->pegawaiStaf->id)
        ->update(['terpakai' => 11, 'saldo_tersedia' => 1]);

    // Pengajuan ke-1 (Kamis, 19 Nov 2026) berhasil masuk antrean menunggu_atasan
    $this->actingAs($this->userStaf)->post(route('pengajuan.store'), [
        'tanggal_cuti' => '2026-11-19',
        'alasan'       => 'Cuti pertama menghabiskan kuota efektif',
    ])->assertRedirect(route('pengajuan.index'));

    // Pengajuan ke-2 (Jumat, 20 Nov 2026) harus ditolak karena saldo efektif = 1 - 1 = 0
    $responseKedua = $this->actingAs($this->userStaf)->post(route('pengajuan.store'), [
        'tanggal_cuti' => '2026-11-20',
        'alasan'       => 'Percobaan overbooking kuota cuti',
    ]);

    $responseKedua->assertSessionHasErrors('tanggal_cuti');
    $this->assertDatabaseCount('pengajuan_cuti', 1);
    }

    public function test_pengajuan_draf_divalidasi_ulang_saat_dikirim(): void
    {
    // Simpan sebagai draf terlebih dahulu
    $this->actingAs($this->userStaf)->post(route('pengajuan.store'), [
        'tanggal_cuti'   => '2026-11-24',
        'alasan'         => 'Rencana acara keluarga',
        'simpan_sebagai' => 'draf',
    ]);

    $pengajuan = PengajuanCuti::first();
    $this->assertEquals('draf', $pengajuan->status);

    // Tiba-tiba Admin HR menetapkan tanggal 2026-11-24 sebagai Hari Libur Perusahaan
    HariLibur::create([
        'tanggal' => '2026-11-24',
        'nama'    => 'Libur Khusus Klinik',
        'jenis'   => 'perusahaan',
        'aktif'   => true,
    ]);

    // Saat staf mencoba mengirim draf tersebut, sistem wajib menolak
    $response = $this->actingAs($this->userStaf)
        ->patch(route('pengajuan.ajukanDraf', $pengajuan->id));

    $response->assertSessionHasErrors('tanggal_cuti');
    $this->assertEquals('draf', $pengajuan->fresh()->status);
    }

    public function test_admin_hr_dapat_membatalkan_cuti_disetujui_sebelum_hari_h_dan_refund_saldo(): void
    {
    $pengajuan = PengajuanCuti::create([
        'nomor_pengajuan'     => 'CT-20261113-RFND',
        'pegawai_id'          => $this->pegawaiStaf->id,
        'atasan_penyetuju_id' => $this->pegawaiAtasan->id,
        'status'              => 'disetujui',
        'alasan'              => 'Cuti terjadwal minggu depan',
        'tanggal_cuti'        => Carbon::tomorrow()->toDateString(),
    ]);

    // Simulasikan saldo sudah terpotong 1 hari (terpakai: 1, saldo_tersedia: 11)
    SaldoCuti::where('pegawai_id', $this->pegawaiStaf->id)
        ->update(['terpakai' => 1, 'saldo_tersedia' => 11]);

    // Admin HR membatalkan pengajuan tersebut sebelum hari-H
    $this->actingAs($this->userHr)
        ->patch(route('pengajuan.batalkanOlehHr', $pengajuan->id))
        ->assertSessionHasNoErrors();

    $this->assertEquals('dibatalkan', $pengajuan->fresh()->status);
    $this->assertDatabaseHas('saldo_cuti', [
        'pegawai_id'     => $this->pegawaiStaf->id,
        'terpakai'       => 0,
        'saldo_tersedia' => 12,
    ]);
    }

}
