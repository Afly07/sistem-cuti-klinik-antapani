<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0d9488">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
    <title>Masuk Sistem — SIMCUTI Klinik Utama Medika Antapani</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen font-sans antialiased text-slate-800 flex items-center justify-center p-4 sm:p-6 lg:p-10 bg-gradient-to-br from-slate-950 via-slate-900 to-teal-950">
    <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-12 rounded-3xl overflow-hidden shadow-2xl border border-slate-800 bg-white">
        <!-- Left Hero Panel -->
        <div class="lg:col-span-6 bg-gradient-to-br from-slate-900 via-slate-900 to-teal-900 text-white p-8 sm:p-10 flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1.5 rounded-full bg-teal-500/15 border border-teal-400/30 text-teal-300 text-xs font-semibold mb-6">
                    <span class="w-2 h-2 rounded-full bg-teal-400"></span>
                    Progressive Web Application (PWA)
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-snug">
                    Sistem Informasi Pengajuan & Persetujuan Cuti Tahunan
                </h1>
                <p class="mt-3 text-sm text-slate-300 leading-relaxed">
                    Digitalisasi pengajuan cuti pegawai <strong>Klinik Utama Medika Antapani</strong> untuk menggantikan proses manual berbasis kertas menjadi terukur, transparan, dan mudah dilacak secara <em>real-time</em>.
                </p>

                <div class="mt-8 space-y-4">
                    <div class="flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-xl bg-teal-500/20 border border-teal-400/30 flex items-center justify-center text-teal-300 font-bold text-xs flex-shrink-0">01</div>
                        <div>
                            <h3 class="text-sm font-bold text-white">Validasi Otomatis Berlapis</h3>
                            <p class="text-xs text-slate-300 mt-0.5">Pemeriksaan otomatis hari kerja (Senin–Jumat), kalender hari libur nasional/klinik, dan sisa kuota cuti tahunan.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-xl bg-cyan-500/20 border border-cyan-400/30 flex items-center justify-center text-cyan-300 font-bold text-xs flex-shrink-0">02</div>
                        <div>
                            <h3 class="text-sm font-bold text-white">Persetujuan Berjenjang (Atasan & HR)</h3>
                            <p class="text-xs text-slate-300 mt-0.5">Pengajuan staf diverifikasi oleh Atasan Divisi sebelum persetujuan akhir dan pemotongan saldo oleh Admin HR.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-xl bg-amber-500/20 border border-amber-400/30 flex items-center justify-center text-amber-300 font-bold text-xs flex-shrink-0">03</div>
                        <div>
                            <h3 class="text-sm font-bold text-white">Monitoring Saldo & Ekspor Laporan CSV</h3>
                            <p class="text-xs text-slate-300 mt-0.5">Pantau sisa jatah cuti seluruh instalasi klinik dan unduh rekapitulasi periodik dalam sekali klik.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 mt-8 pt-6 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                <span>Klinik Utama Medika Antapani</span>
                <span>1 Pengajuan = 1 Hari Cuti Tahunan</span>
            </div>
        </div>

        <!-- Right Login Form Panel -->
        <div class="lg:col-span-6 p-8 sm:p-10 flex flex-col justify-center bg-white">
            <div class="mb-6">
                <span class="text-xs font-bold uppercase tracking-wider text-teal-600">Portal Pegawai</span>
                <h2 class="text-2xl font-extrabold text-slate-900 mt-1">Masuk ke Akun Anda</h2>
                <p class="text-xs text-slate-500 mt-1">Gunakan email resmi klinik dan kata sandi Anda untuk melanjutkan.</p>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-xs font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-xs font-semibold text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="staf@klinikantapani.id"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-teal-600 focus:ring-2 focus:ring-teal-500/20 outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm text-slate-900 focus:border-teal-600 focus:ring-2 focus:ring-teal-500/20 outline-none transition">
                </div>
                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm shadow-lg shadow-teal-600/25 transition">
                    Masuk Sistem Sekarang
                </button>
            </form>

            <!-- Quick Role Switcher for Demo / Pengujian -->
            <div class="mt-7 pt-6 border-t border-slate-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-slate-700">Akses Cepat Akun Demo (1-Klik)</span>
                    <span class="text-[11px] text-slate-400">Password: <code class="text-slate-600 font-semibold">password</code></span>
                </div>
                <div class="grid grid-cols-1 gap-2">
                    <a href="{{ url('/login-sebagai/karyawan') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl border border-slate-200 hover:border-teal-500 hover:bg-teal-50/50 transition group">
                        <div>
                            <p class="text-xs font-bold text-slate-800 group-hover:text-teal-700">Staf / Karyawan — Budi Santoso, S.Farm.</p>
                            <p class="text-[11px] text-slate-500">Instalasi Farmasi • staf@klinikantapani.id</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-teal-100 text-teal-800 text-[11px] font-bold">Masuk Staf →</span>
                    </a>
                    <a href="{{ url('/login-sebagai/atasan') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl border border-slate-200 hover:border-cyan-500 hover:bg-cyan-50/50 transition group">
                        <div>
                            <p class="text-xs font-bold text-slate-800 group-hover:text-cyan-700">Atasan Divisi — dr. Andi Pratama, Sp.PD</p>
                            <p class="text-[11px] text-slate-500">Ka. Instalasi Farmasi • atasan@klinikantapani.id</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-cyan-100 text-cyan-800 text-[11px] font-bold">Masuk Atasan →</span>
                    </a>
                    <a href="{{ url('/login-sebagai/admin_hr') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl border border-slate-200 hover:border-amber-500 hover:bg-amber-50/50 transition group">
                        <div>
                            <p class="text-xs font-bold text-slate-800 group-hover:text-amber-700">Admin HR — Siti Rahma, S.Psi.</p>
                            <p class="text-[11px] text-slate-500">SDM & Umum • hr@klinikantapani.id</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-amber-100 text-amber-800 text-[11px] font-bold">Masuk HR →</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js').catch(() => {});
        }
    </script>
</body>
</html>
