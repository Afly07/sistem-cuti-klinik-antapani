<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f766e">
    <link rel="manifest" href="/manifest.json">
    <title>@yield('title', 'Sistem Cuti') - Klinik Utama Medika Antapani</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">
    @php
        $user = auth()->user();
        $peranKode = $user->peran->kode ?? 'karyawan';
    @endphp

    <header class="bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            <a href="{{ route('dashboard') }}" class="font-bold text-base text-teal-700 tracking-tight whitespace-nowrap">
                Klinik Medika Antapani
            </a>

            @auth
            <nav class="hidden md:flex items-center gap-5 text-sm font-medium">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-teal-700 font-semibold' : 'text-slate-600 hover:text-slate-900' }}">Dashboard</a>
                <a href="{{ route('pengajuan.create') }}" class="{{ request()->routeIs('pengajuan.create') ? 'text-teal-700 font-semibold' : 'text-slate-600 hover:text-slate-900' }}">Ajukan Cuti</a>
                <a href="{{ route('pengajuan.index') }}" class="{{ request()->routeIs('pengajuan.index') || request()->routeIs('pengajuan.show') ? 'text-teal-700 font-semibold' : 'text-slate-600 hover:text-slate-900' }}">Riwayat</a>

                @if($peranKode === 'atasan')
                    <a href="{{ route('persetujuan.atasan') }}" class="{{ request()->routeIs('persetujuan.atasan') ? 'text-teal-700 font-semibold' : 'text-slate-600 hover:text-slate-900' }}">Persetujuan Atasan</a>
                @endif

                @if($peranKode === 'admin_hr')
                    <a href="{{ route('persetujuan.hr') }}" class="{{ request()->routeIs('persetujuan.hr') ? 'text-teal-700 font-semibold' : 'text-slate-600 hover:text-slate-900' }}">Persetujuan HR</a>
                    <a href="{{ route('pegawai.index') }}" class="{{ request()->routeIs('pegawai.*') ? 'text-teal-700 font-semibold' : 'text-slate-600 hover:text-slate-900' }}">Pegawai</a>
                    <a href="{{ route('hari-libur.index') }}" class="{{ request()->routeIs('hari-libur.*') ? 'text-teal-700 font-semibold' : 'text-slate-600 hover:text-slate-900' }}">Hari Libur</a>
                    <a href="{{ route('laporan.index') }}" class="{{ request()->routeIs('laporan.*') ? 'text-teal-700 font-semibold' : 'text-slate-600 hover:text-slate-900' }}">Laporan</a>
                @endif
            </nav>

            <div class="flex items-center gap-3 text-sm">
                <a href="{{ route('profil.index') }}" class="text-slate-700 hover:text-teal-700 font-medium hidden sm:inline">
                    {{ $user->pegawai->nama ?? $user->nama }}
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100 text-sm font-medium">
                        Keluar
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </header>

    <main class="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 py-8">
        @if(session('success'))
            <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-lg bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-800">
                {{ $errors->first() }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white py-4 text-center text-sm text-slate-500">
        &copy; {{ date('Y') }} Klinik Utama Medika Antapani
    </footer>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js').catch(() => {}));
        }
    </script>
</body>
</html>