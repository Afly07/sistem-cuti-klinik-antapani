<?php

namespace App\Http\Controllers;

use App\Models\HariLibur;
use App\Models\Pegawai;
use App\Models\PengajuanCuti;
use App\Models\Pengguna;
use App\Models\PersetujuanCuti;
use App\Models\SaldoCuti;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\BagianOrganisasi;

class PengajuanCutiController extends Controller
{
    /**
     * Menghitung saldo efektif = saldo_tersedia dikurangi pengajuan yang masih menggantung.
     */
    protected function hitungSaldoEfektif(int $pegawaiId, int $tahun, ?int $abaikanPengajuanId = null): int
    {
        $saldo = SaldoCuti::where('pegawai_id', $pegawaiId)
            ->where('tahun', $tahun)
            ->first();

        if (!$saldo) {
            return 0;
        }

        $queryMenggantung = PengajuanCuti::where('pegawai_id', $pegawaiId)
            ->whereYear('tanggal_cuti', $tahun)
            ->whereIn('status', ['menunggu_atasan', 'menunggu_hr']);

        if ($abaikanPengajuanId) {
            $queryMenggantung->where('id', '!=', $abaikanPengajuanId);
        }

        $jumlahMenggantung = $queryMenggantung->count();

        return max(0, $saldo->saldo_tersedia - $jumlahMenggantung);
    }

    /**
     * Validasi terpusat untuk pengajuan baru maupun saat mengirim draf.
     */
    protected function validasiTanggalDanSaldo(
        Pegawai $pegawai,
        string $tanggalInput,
        bool $wajibCekSaldoEfektif = true,
        ?int $abaikanPengajuanId = null
    ): void {
        $tanggal = Carbon::parse($tanggalInput)->startOfDay();

        // 1. Tolak jika tanggal sudah lewat dari hari ini
        if ($tanggal->lessThan(Carbon::today())) {
            throw ValidationException::withMessages([
                'tanggal_cuti' => 'Tanggal cuti tidak boleh berada di masa lalu.',
            ]);
        }

        // 2. Tolak jika jatuh pada hari Sabtu atau Minggu
        if ($tanggal->isWeekend()) {
            throw ValidationException::withMessages([
                'tanggal_cuti' => 'Cuti tidak dapat diambil pada hari Sabtu atau Minggu.',
            ]);
        }

        // 3. Tolak jika jatuh pada hari libur nasional/klinik yang aktif
        if (HariLibur::where('tanggal', $tanggal->toDateString())->where('aktif', true)->exists()) {
            throw ValidationException::withMessages([
                'tanggal_cuti' => 'Tanggal yang dipilih merupakan hari libur nasional/klinik.',
            ]);
        }

        // 4. Tolak jika pegawai sudah punya pengajuan aktif di tanggal yang sama
        $duplikatQuery = PengajuanCuti::where('pegawai_id', $pegawai->id)
            ->where('tanggal_cuti', $tanggal->toDateString())
            ->whereIn('status', ['draf', 'menunggu_atasan', 'menunggu_hr', 'disetujui']);

        if ($abaikanPengajuanId) {
            $duplikatQuery->where('id', '!=', $abaikanPengajuanId);
        }

        if ($duplikatQuery->exists()) {
            throw ValidationException::withMessages([
                'tanggal_cuti' => 'Anda sudah memiliki pengajuan cuti aktif pada tanggal tersebut.',
            ]);
        }

        // 5. Cek kecukupan saldo efektif (mencegah overbooking saat ada pengajuan menggantung)
        if ($wajibCekSaldoEfektif) {
            $tahun = (int) $tanggal->format('Y');
            $saldoEfektif = $this->hitungSaldoEfektif($pegawai->id, $tahun, $abaikanPengajuanId);

            if ($saldoEfektif < 1) {
                throw ValidationException::withMessages([
                    'tanggal_cuti' => 'Saldo cuti tahunan Anda tidak mencukupi (termasuk pengajuan yang sedang menunggu persetujuan).',
                ]);
            }
        }
    }

    /**
     * Menentukan status awal berdasarkan peran dan ketersediaan atasan_id.
     */
    protected function tentukanStatusAwal(Pengguna $pengguna, Pegawai $pegawai): string
    {
        if (in_array($pengguna->peran->kode, ['atasan', 'admin_hr'], true) || is_null($pegawai->atasan_id)) {
            return 'menunggu_hr';
        }

        return 'menunggu_atasan';
    }

    public function dashboard(Request $request)
    {
        $pengguna = $request->user();
        $pegawai  = $pengguna->pegawai;
        $tahun    = date('Y');

        $saldo = SaldoCuti::firstOrCreate(
            ['pegawai_id' => $pegawai->id, 'tahun' => $tahun],
            ['jatah_awal' => 12, 'terpakai' => 0, 'saldo_tersedia' => 12]
        );

        // 1. Riwayat terbaru milik user yang login (untuk tabel "Pengajuan Cuti Terbaru Saya")
        $riwayatTerbaru = PengajuanCuti::where('pegawai_id', $pegawai->id)
            ->latest('diajukan_pada')
            ->take(5)
            ->get();

        // 2. Hari libur mendatang (untuk panel kanan "Hari Libur Mendatang")
        $hariLiburMendatang = HariLibur::where('aktif', true)
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->take(5)
            ->get();

        // 3. Staf bawahan (untuk tabel "Monitoring Sisa Saldo Cuti Staf")
        $stafBawahan = collect();
        if (in_array($pengguna->peran->kode, ['atasan', 'admin_hr'])) {
            $query = Pegawai::with(['saldoCuti' => fn($q) => $q->where('tahun', $tahun), 'bagianOrganisasi']);
            if ($pengguna->peran->kode === 'atasan') {
                $query->where('atasan_id', $pegawai->id);
            }
            $stafBawahan = $query->get();
        }

        // 4. Key lengkap $ringkasanKlinik persis sesuai dashboard.blade.php baris 114-129
        $ringkasanKlinik = [
            'total_pegawai'   => Pegawai::where('aktif', true)->count(),
            'total_bagian'    => \App\Models\BagianOrganisasi::where('aktif', true)->count(),
            'cuti_disetujui'  => PengajuanCuti::where('status', 'disetujui')->count(),
            'menunggu_proses' => PengajuanCuti::whereIn('status', ['menunggu_atasan', 'menunggu_hr'])->count(),
        ];

        return view('dashboard', compact(
            'saldo',
            'stafBawahan',
            'ringkasanKlinik',
            'riwayatTerbaru',
            'hariLiburMendatang'
        ));
    }

    public function show(PengajuanCuti $pengajuan)
    {
        return redirect()->route('pengajuan.index');
    }

    public function index(Request $request)
    {
        $riwayat = PengajuanCuti::where('pegawai_id', $request->user()->pegawai->id)
            ->latest('diajukan_pada')
            ->get();

        return view('pengajuan.index', compact('riwayat'));
    }

    public function create(Request $request)
    {
        $saldo = SaldoCuti::where('pegawai_id', $request->user()->pegawai->id)
            ->where('tahun', date('Y'))
            ->first();

        return view('pengajuan.create', compact('saldo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_cuti'   => ['required', 'date', 'after_or_equal:today'],
            'alasan'         => ['required', 'string', 'max:500'],
            'simpan_sebagai' => ['nullable', 'in:draf,ajukan'],
        ]);

        $pengguna = $request->user();
        $pegawai  = $pengguna->pegawai;
        $sebagaiDraf = $request->input('simpan_sebagai') === 'draf';

        // Jalankan validasi kalender, hari libur, duplikasi, dan saldo efektif
        $this->validasiTanggalDanSaldo($pegawai, $request->tanggal_cuti, !$sebagaiDraf);

        $statusAwal = $sebagaiDraf
            ? 'draf'
            : $this->tentukanStatusAwal($pengguna, $pegawai);

        PengajuanCuti::create([
            'nomor_pengajuan'     => 'CT-' . date('Ymd') . '-' . strtoupper(Str::random(4)),
            'pegawai_id'          => $pegawai->id,
            'atasan_penyetuju_id' => $pegawai->atasan_id,
            'status'              => $statusAwal,
            'alasan'              => $request->alasan,
            'tanggal_cuti'        => $request->tanggal_cuti,
            'diajukan_pada'       => now(),
        ]);

        return redirect()->route('pengajuan.index')->with(
            'success',
            $sebagaiDraf ? 'Pengajuan cuti disimpan sebagai draf.' : 'Pengajuan cuti berhasil dikirim.'
        );
    }

    public function ajukanDraf(Request $request, PengajuanCuti $pengajuan)
    {
        Gate::authorize('ajukanDraf', $pengajuan);

        $pengguna = $request->user();
        $pegawai  = $pengguna->pegawai;

        // Validasi ulang tanggal & saldo efektif sebelum draf benar-benar dikirim
        $this->validasiTanggalDanSaldo($pegawai, $pengajuan->tanggal_cuti, true, $pengajuan->id);

        $pengajuan->update([
            'atasan_penyetuju_id' => $pegawai->atasan_id,
            'status'              => $this->tentukanStatusAwal($pengguna, $pegawai),
            'diajukan_pada'       => now(),
        ]);

        return back()->with('success', 'Draf pengajuan cuti berhasil dikirim untuk diproses.');
    }

    public function halamanAtasan(Request $request)
    {
        abort_unless($request->user()->peran->kode === 'atasan', 403);

        $antreanAtasan = PengajuanCuti::with('pegawai')
            ->where('atasan_penyetuju_id', $request->user()->pegawai->id)
            ->where('status', 'menunggu_atasan')
            ->get();

        return view('persetujuan.atasan', compact('antreanAtasan'));
    }

    public function keputusanAtasan(Request $request, PengajuanCuti $pengajuan)
    {
        Gate::authorize('setujuiAtasan', $pengajuan);

        $request->validate([
            'keputusan' => 'required|in:disetujui,ditolak',
            'catatan'   => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $pengajuan) {
            $pengajuan->update([
                'status' => $request->keputusan === 'disetujui' ? 'menunggu_hr' : 'ditolak',
            ]);

            PersetujuanCuti::create([
                'pengajuan_cuti_id'    => $pengajuan->id,
                'pemberi_keputusan_id' => $request->user()->pegawai->id,
                'tahap'                => 'atasan',
                'keputusan'            => $request->keputusan,
                'catatan'              => $request->catatan,
                'diputuskan_pada'      => now(),
            ]);
        });

        return back()->with('success', 'Keputusan tahap atasan berhasil disimpan.');
    }

    public function halamanHr(Request $request)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $antreanHr = PengajuanCuti::with('pegawai')
            ->where('status', 'menunggu_hr')
            ->get();

        return view('persetujuan.hr', compact('antreanHr'));
    }

    public function keputusanHr(Request $request, PengajuanCuti $pengajuan)
    {
        Gate::authorize('setujuiHr', $pengajuan);

        $request->validate([
            'keputusan' => 'required|in:disetujui,ditolak',
            'catatan'   => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $pengajuan) {
            if ($request->keputusan === 'disetujui') {
                $tahun = (int) date('Y', strtotime($pengajuan->tanggal_cuti));
                $saldo = SaldoCuti::where('pegawai_id', $pengajuan->pegawai_id)
                    ->where('tahun', $tahun)
                    ->lockForUpdate()
                    ->first();

                if (!$saldo || $saldo->saldo_tersedia < 1) {
                    throw ValidationException::withMessages([
                        'keputusan' => 'Gagal menyetujui: Saldo cuti pegawai sudah habis.',
                    ]);
                }

                $saldo->increment('terpakai', 1);
                $saldo->decrement('saldo_tersedia', 1);
            }

            $pengajuan->update(['status' => $request->keputusan]);

            PersetujuanCuti::create([
                'pengajuan_cuti_id'    => $pengajuan->id,
                'pemberi_keputusan_id' => $request->user()->pegawai->id,
                'tahap'                => 'admin_hr',
                'keputusan'            => $request->keputusan,
                'catatan'              => $request->catatan,
                'diputuskan_pada'      => now(),
            ]);
        });

        return back()->with('success', 'Keputusan akhir HR disimpan dan saldo cuti diperbarui.');
    }

    public function batalkan(Request $request, PengajuanCuti $pengajuan)
    {
        Gate::authorize('batalkan', $pengajuan);

        $pengajuan->update([
            'status'          => 'dibatalkan',
            'dibatalkan_pada' => now(),
        ]);

        return back()->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }

    /**
     * Pembatalan oleh Admin HR untuk cuti yang sudah disetujui sebelum hari-H disertai Refund Saldo.
     */
    public function batalkanOlehHr(Request $request, PengajuanCuti $pengajuan)
    {
        Gate::authorize('batalkanOlehHr', $pengajuan);

        DB::transaction(function () use ($pengajuan) {
            $tahun = (int) date('Y', strtotime($pengajuan->tanggal_cuti));
            $saldo = SaldoCuti::where('pegawai_id', $pengajuan->pegawai_id)
                ->where('tahun', $tahun)
                ->lockForUpdate()
                ->first();

            if ($saldo && $saldo->terpakai > 0) {
                $saldo->decrement('terpakai', 1);
                $saldo->increment('saldo_tersedia', 1);
            }

            $pengajuan->update([
                'status'          => 'dibatalkan',
                'dibatalkan_pada' => now(),
            ]);
        });

        return back()->with('success', 'Cuti yang telah disetujui berhasil dibatalkan dan 1 hari saldo cuti dikembalikan.');
    }

    public function eksporCsv(Request $request)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $dataCuti = PengajuanCuti::with(['pegawai.bagianOrganisasi'])
            ->latest('diajukan_pada')
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rekap_cuti_klinik_antapani_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($dataCuti) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Nomor Pengajuan', 'NIP', 'Nama Pegawai', 'Bagian Organisasi', 'Tanggal Cuti', 'Alasan', 'Status', 'Diajukan Pada']);

            foreach ($dataCuti as $row) {
                fputcsv($file, [
                    $row->nomor_pengajuan,
                    $row->pegawai->nomor_induk ?? '-',
                    $row->pegawai->nama ?? '-',
                    $row->pegawai->bagianOrganisasi->nama ?? '-',
                    $row->tanggal_cuti,
                    $row->alasan,
                    $row->status,
                    $row->diajukan_pada,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function laporan(Request $request)
    {
        abort_unless($request->user()->peran->kode === 'admin_hr', 403);

        $query = PengajuanCuti::with(['pegawai.bagianOrganisasi', 'atasanPenyetuju', 'persetujuan.pemberiKeputusan']);

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('bagian_organisasi_id')) {
            $query->whereHas('pegawai', fn($q) => $q->where('bagian_organisasi_id', $request->bagian_organisasi_id));
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_cuti', $request->tahun);
        }

        $dataCuti     = $query->latest('diajukan_pada')->get();
        $daftarBagian = BagianOrganisasi::orderBy('nama')->get();

        $statistik = [
            'total'         => $dataCuti->count(),
            'disetujui'     => $dataCuti->where('status', 'disetujui')->count(),
            'menunggu'      => $dataCuti->whereIn('status', ['menunggu_atasan', 'menunggu_hr'])->count(),
            'ditolak_batal' => $dataCuti->whereIn('status', ['ditolak', 'dibatalkan'])->count(),
        ];

        return view('laporan.index', compact('dataCuti', 'daftarBagian', 'statistik'));
    }
}