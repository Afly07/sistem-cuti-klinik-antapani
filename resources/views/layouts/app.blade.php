<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <link rel="manifest" href="/manifest.json">
    <title>@yield('title', 'Sistem Cuti') — Klinik Utama Medika Antapani</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
    <style>
        .tabular-nums { font-variant-numeric: tabular-nums; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col">
    @php
        $user = auth()->user();
        $peranKode = $user->peran->kode ?? 'karyawan';
    @endphp

    <!-- Topbar Navy Minimalis -->
    <header class="no-print bg-slate-900 text-white border-b border-slate-800">
        <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between gap-6">
            <a href="{{ route('dashboard') }}" class="text-base font-bold tracking-tight text-white flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                <span>Klinik Medika Antapani</span>
            </a>

            @auth
            <nav class="hidden md:flex items-center gap-1 text-sm font-medium">
                <a href="{{ route('dashboard') }}" class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-cyan-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">Dashboard</a>
                <a href="{{ route('pengajuan.create') }}" class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('pengajuan.create') ? 'bg-slate-800 text-cyan-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">Ajukan Cuti</a>
                <a href="{{ route('pengajuan.index') }}" class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('pengajuan.index') || request()->routeIs('pengajuan.show') ? 'bg-slate-800 text-cyan-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">Riwayat</a>

                @if($peranKode === 'atasan')
                    <a href="{{ route('persetujuan.atasan') }}" class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('persetujuan.atasan') ? 'bg-slate-800 text-cyan-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">Persetujuan</a>
                @endif

                @if($peranKode === 'admin_hr')
                    <a href="{{ route('persetujuan.hr') }}" class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('persetujuan.hr') ? 'bg-slate-800 text-cyan-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">Persetujuan HR</a>
                    <a href="{{ route('saldo-cuti.index') }}" class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('saldo-cuti.*') ? 'bg-slate-800 text-cyan-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">Saldo</a>
                    <a href="{{ route('pegawai.index') }}" class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('pegawai.*') ? 'bg-slate-800 text-cyan-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">Pegawai</a>
                    <a href="{{ route('hari-libur.index') }}" class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('hari-libur.*') ? 'bg-slate-800 text-cyan-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">Hari Libur</a>
                    <a href="{{ route('laporan.index') }}" class="px-3.5 py-2 rounded-lg transition {{ request()->routeIs('laporan.*') ? 'bg-slate-800 text-cyan-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">Laporan</a>
                @endif
            </nav>

            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('profil.index') }}" class="text-slate-200 hover:text-cyan-400 font-medium hidden sm:inline">
                    {{ $user->pegawai->nama ?? $user->nama }}
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-medium transition">
                        Keluar
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="flex-1 max-w-5xl w-full mx-auto px-6 py-10">
        @if(session('success'))
            <div class="mb-8 rounded-lg bg-cyan-50 border border-cyan-200 px-5 py-3.5 text-sm font-medium text-cyan-950">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 rounded-lg bg-red-50 border border-red-200 px-5 py-3.5 text-sm font-medium text-red-900">
                {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer Bersih + Pindah Akun Demo -->
    <footer class="no-print border-t border-slate-200 bg-white">
        <div class="max-w-5xl mx-auto px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-slate-500">
            <span>&copy; {{ date('Y') }} Klinik Utama Medika Antapani</span>
            @auth
            <div class="flex items-center gap-3">
                <span>Pindah Akun:</span>
                <a href="{{ url('/login-sebagai/karyawan') }}" class="text-slate-700 hover:text-cyan-700 font-medium">Staf</a>
                <span>·</span>
                <a href="{{ url('/login-sebagai/atasan') }}" class="text-slate-700 hover:text-cyan-700 font-medium">Atasan</a>
                <span>·</span>
                <a href="{{ url('/login-sebagai/admin_hr') }}" class="text-slate-700 hover:text-cyan-700 font-medium">Admin HR</a>
            </div>
            @endauth
        </div>
    </footer>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js').catch(() => {}));
        }
    </script>
</body>
</html>