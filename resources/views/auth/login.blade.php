<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f172a">
    <link rel="manifest" href="/manifest.json">
    <title>Masuk — Klinik Utama Medika Antapani</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'] } } }
        }
    </script>
</head>
<body class="bg-slate-900 text-slate-900 antialiased min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden">
        <div class="px-8 pt-8 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-2.5 h-2.5 rounded-full bg-cyan-500"></span>
                <span class="text-sm font-semibold text-cyan-700">Sistem Cuti Tahunan</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Klinik Medika Antapani</h1>
        </div>

        <div class="p-8">
            @if($errors->any())
                <div class="mb-5 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm font-medium text-red-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@klinikantapani.id"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-base text-slate-900 focus:border-cyan-600 focus:ring-2 focus:ring-cyan-500/20 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-2">Kata Sandi</label>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-base text-slate-900 focus:border-cyan-600 focus:ring-2 focus:ring-cyan-500/20 outline-none">
                </div>

                <button type="submit" class="w-full py-3 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-semibold text-base transition">
                    Masuk
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-200">
                <p class="text-sm font-medium text-slate-600 mb-3">Akses Cepat Demo</p>
                <div class="grid grid-cols-3 gap-2.5 text-sm">
                    <a href="{{ url('/login-sebagai/karyawan') }}" class="text-center py-2.5 rounded-lg border border-slate-300 hover:border-cyan-600 hover:text-cyan-700 font-semibold text-slate-700 transition">Staf</a>
                    <a href="{{ url('/login-sebagai/atasan') }}" class="text-center py-2.5 rounded-lg border border-slate-300 hover:border-cyan-600 hover:text-cyan-700 font-semibold text-slate-700 transition">Atasan</a>
                    <a href="{{ url('/login-sebagai/admin_hr') }}" class="text-center py-2.5 rounded-lg border border-slate-300 hover:border-cyan-600 hover:text-cyan-700 font-semibold text-slate-700 transition">Admin HR</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>