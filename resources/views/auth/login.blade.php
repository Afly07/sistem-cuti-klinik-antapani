<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0f766e">
    <link rel="manifest" href="/manifest.json">
    <title>Masuk - Sistem Cuti Klinik Utama Medika Antapani</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-xl border border-slate-200 shadow-sm p-8">
        <div class="text-center mb-6">
            <h1 class="text-xl font-bold text-slate-900">Klinik Utama Medika Antapani</h1>
            <p class="text-sm text-slate-500 mt-1">Sistem Pengajuan Cuti Tahunan</p>
        </div>

        @if($errors->any())
            <div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 px-4 py-2.5 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@klinikantapani.id"
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-600 focus:ring-1 focus:ring-teal-600 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Kata Sandi</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-teal-600 focus:ring-1 focus:ring-teal-600 outline-none">
            </div>
            <button type="submit" class="w-full py-2.5 rounded-lg bg-teal-700 hover:bg-teal-800 text-white font-semibold text-sm transition">
                Masuk
            </button>
        </form>

        <div class="mt-6 pt-5 border-t border-slate-200">
            <p class="text-sm text-slate-500 text-center mb-3">Akses Cepat Demo (Password: <code>password</code>)</p>
            <div class="grid grid-cols-3 gap-2 text-sm">
                <a href="{{ url('/login-sebagai/karyawan') }}" class="text-center py-2 rounded-lg border border-slate-200 hover:bg-slate-50 font-medium text-slate-700">Staf</a>
                <a href="{{ url('/login-sebagai/atasan') }}" class="text-center py-2 rounded-lg border border-slate-200 hover:bg-slate-50 font-medium text-slate-700">Atasan</a>
                <a href="{{ url('/login-sebagai/admin_hr') }}" class="text-center py-2 rounded-lg border border-slate-200 hover:bg-slate-50 font-medium text-slate-700">Admin HR</a>
            </div>
        </div>
    </div>
</body>
</html>