<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0d9488">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <title>@yield('title', 'SIMCUTI') — Klinik Utama Medika Antapani</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        medika: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        },
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            .no-print { display: none !important; }
            main { padding: 0 !important; }
        }
    </style>
</head>
<body class="h-full font-sans text-slate-800 antialiased selection:bg-teal-600 selection:text-white">
    @php
        $user = auth()->user();
        $peranKode = $user->peran->kode ?? 'karyawan';
        $namaPeran = match($peranKode) {
            'admin_hr' => 'Admin HR',
            'atasan'   => 'Atasan Divisi',
            default    => 'Staf / Karyawan',
        };
        $badgePeranClass = match($peranKode) {
            'admin_hr' => 'bg-amber-500/20 text-amber-300 border-amber-400/30',
            'atasan'   => 'bg-cyan-500/20 text-cyan-300 border-cyan-400/30',
            default    => 'bg-teal-500/20 text-teal-300 border-teal-400/30',
        };
        $jmlAntreanAtasan = 0;
        $jmlAntreanHr = 0;
        if ($user && $user->pegawai) {
            if ($peranKode === 'atasan') {
                $jmlAntreanAtasan = \App\Models\PengajuanCuti::where('atasan_penyetuju_id', $user->pegawai->id)->where('status', 'menunggu_atasan')->count();
            }
            if ($peranKode === 'admin_hr') {
                $jmlAntreanHr = \App\Models\PengajuanCuti::where('status', 'menunggu_hr')->count();
            }
        }
    @endphp

    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Sidebar Desktop -->
        <aside class="no-print w-full lg:w-72 bg-navy-900 text-slate-200 flex-shrink-0 flex flex-col justify-between border-r border-slate-800">
            <div>
                <!-- Brand Header -->
                <div class="px-6 py-5 flex items-center gap-3.5 border-b border-slate-800/80">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center shadow-lg shadow-teal-500/20 flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-widest text-teal-400 block">SIMCUTI PWA</span>
                        <h1 class="text-sm font-bold text-white tracking-tight leading-tight">Klinik Utama Medika Antapani</h1>
                    </div>
                </div>

                @auth
                <!-- User Mini Card -->
                <div class="px-5 py-4 border-b border-slate-800/80 bg-slate-900/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-teal-600/20 border border-teal-500/40 text-teal-300 flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr($user->pegawai->nama ?? $user->nama, 0, 2)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-white truncate">{{ $user->pegawai->nama ?? $user->nama }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $user->pegawai->bagianOrganisasi->nama ?? 'Klinik Medika' }} • {{ $user->pegawai->nomor_induk ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $badgePeranClass }}">
                            {{ $namaPeran }}
                        </span>
                        <a href="{{ route('profil.index') }}" class="text-xs text-slate-400 hover:text-teal-400 transition">Lihat Profil →</a>
                    </div>
                </div>

                <!-- Navigation Links -->
                <nav class="px-3 py-4 space-y-1 text-sm">
                    <p class="px-3 pt-1 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">Menu Utama</p>

                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('dashboard') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard & Saldo</span>
                    </a>

                    <a href="{{ route('pengajuan.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('pengajuan.create') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Ajukan Cuti</span>
                    </a>

                    <a href="{{ route('pengajuan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('pengajuan.index') || request()->routeIs('pengajuan.show') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Riwayat Saya</span>
                    </a>

                    <a href="{{ route('profil.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('profil.*') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Profil & Saldo Cuti</span>
                    </a>

                    <a href="{{ route('jenis-cuti.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('jenis-cuti.*') ? 'bg-teal-600 text-white shadow-sm shadow-teal-600/30' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Jenis & Aturan Cuti</span>
                    </a>

                    @if($peranKode === 'atasan')
                        <p class="px-3 pt-4 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-cyan-400">Otorisasi Atasan Divisi</p>
                        <a href="{{ route('persetujuan.atasan') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('persetujuan.atasan') ? 'bg-cyan-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Pengajuan Bawahan</span>
                            </div>
                            @if($jmlAntreanAtasan > 0)
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-amber-500 text-slate-950">{{ $jmlAntreanAtasan }}</span>
                            @endif
                        </a>
                    @endif

                    @if($peranKode === 'admin_hr')
                        <p class="px-3 pt-4 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-amber-400">Manajemen Admin HR</p>

                        <a href="{{ route('persetujuan.hr') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('persetujuan.hr') ? 'bg-teal-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span>Persetujuan Akhir HR</span>
                            </div>
                            @if($jmlAntreanHr > 0)
                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-amber-500 text-slate-950">{{ $jmlAntreanHr }}</span>
                            @endif
                        </a>

                        <a href="{{ route('saldo-cuti.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('saldo-cuti.*') ? 'bg-teal-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Saldo Cuti Tahunan</span>
                        </a>

                        <a href="{{ route('pegawai.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('pegawai.*') ? 'bg-teal-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Pengguna & Pegawai</span>
                        </a>

                        <a href="{{ route('bagian-organisasi.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('bagian-organisasi.*') ? 'bg-teal-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>Bagian Organisasi</span>
                        </a>

                        <a href="{{ route('hari-libur.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('hari-libur.*') ? 'bg-teal-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Master Hari Libur</span>
                        </a>

                        <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('laporan.*') ? 'bg-teal-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Rekap & Ekspor CSV</span>
                        </a>
                    @endif
                </nav>
                @endauth
            </div>

            <!-- Role Switcher & PWA Status Footer -->
            @auth
            <div class="p-4 border-t border-slate-800/80 bg-slate-950/60 space-y-3">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Pindah Cepat Akun Demo</p>
                    <div class="grid grid-cols-3 gap-1.5 text-[11px]">
                        <a href="{{ url('/login-sebagai/karyawan') }}" class="text-center py-1.5 px-2 rounded-lg border {{ $peranKode === 'karyawan' ? 'bg-teal-600/30 border-teal-500 text-teal-200 font-bold' : 'border-slate-700 text-slate-400 hover:bg-slate-800 hover:text-white' }}">Staf</a>
                        <a href="{{ url('/login-sebagai/atasan') }}" class="text-center py-1.5 px-2 rounded-lg border {{ $peranKode === 'atasan' ? 'bg-cyan-600/30 border-cyan-500 text-cyan-200 font-bold' : 'border-slate-700 text-slate-400 hover:bg-slate-800 hover:text-white' }}">Atasan</a>
                        <a href="{{ url('/login-sebagai/admin_hr') }}" class="text-center py-1.5 px-2 rounded-lg border {{ $peranKode === 'admin_hr' ? 'bg-amber-600/30 border-amber-500 text-amber-200 font-bold' : 'border-slate-700 text-slate-400 hover:bg-slate-800 hover:text-white' }}">Admin HR</a>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-300 border border-rose-500/20 text-xs font-semibold transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
            @endauth
        </aside>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar -->
            <header class="no-print bg-white border-b border-slate-200/80 sticky top-0 z-20 px-4 sm:px-8 py-3.5 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">@yield('title', 'Dashboard Cuti')</h2>
                    <p class="text-xs text-slate-500">@yield('subtitle', 'Sistem Informasi Pengajuan & Persetujuan Cuti Tahunan Klinik Utama Medika Antapani')</p>
                </div>
                <div class="flex items-center gap-3">
                    <button id="pwa-install-btn" type="button" class="hidden items-center gap-1.5 px-3 py-1.5 rounded-xl bg-teal-50 text-teal-700 border border-teal-200 text-xs font-semibold hover:bg-teal-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Install PWA</span>
                    </button>
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-medium">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        PWA Ready • {{ date('d M Y') }}
                    </span>
                </div>
            </header>

            <!-- Content Container -->
            <main class="flex-1 px-4 sm:px-8 py-6 max-w-7xl w-full mx-auto">
                @if(session('success'))
                    <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 flex items-start gap-3 text-emerald-900 shadow-sm">
                        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="text-sm font-medium flex-1">{{ session('success') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-200 p-4 flex items-start gap-3 text-rose-900 shadow-sm">
                        <svg class="w-5 h-5 text-rose-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="text-sm font-medium flex-1">
                            <p class="font-bold mb-1">Perhatian:</p>
                            <ul class="list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="no-print border-t border-slate-200/80 bg-white px-4 sm:px-8 py-4 text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <span>&copy; {{ date('Y') }} <strong>Klinik Utama Medika Antapani</strong> — Jl. Terusan Jakarta, Antapani, Bandung.</span>
                <span>Pendekatan Progressive Web App (PWA) • Laravel & Tailwind CSS</span>
            </footer>
        </div>
    </div>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }
        let deferredPrompt;
        const installBtn = document.getElementById('pwa-install-btn');
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (installBtn) {
                installBtn.classList.remove('hidden');
                installBtn.classList.add('inline-flex');
            }
        });
        if (installBtn) {
            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                deferredPrompt.prompt();
                await deferredPrompt.userChoice;
                deferredPrompt = null;
                installBtn.classList.add('hidden');
            });
        }
    </script>
</body>
</html>
