<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0d9488">
    <link rel="manifest" href="/manifest.json">
    <title>Login — SIMCUTI Klinik Utama Medika Antapani</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-slate-950 via-slate-900 to-teal-950 text-slate-800">
    <div class="w-full max-w-4xl grid grid-cols-1 lg:grid-cols-12 rounded-3xl overflow-hidden shadow-2xl bg-white">
        <div class="lg:col-span-6 bg-slate-900 text-white p-8 flex flex-col justify-between">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-teal-500/20 text-teal-300 text-xs font-bold mb-4">Progressive Web App (PWA)</span>
                <h1 class="text-2xl font-extrabold leading-snug">Klinik Utama Medika Antapani</h1>
                <p class="mt-2 text-xs text-slate-300 leading-relaxed">Sistem Informasi Pengajuan & Persetujuan Cuti Tahunan Berjenjang (Staf, Atasan Divisi, dan Admin HR).</p>
            </div>
            <p class="text-[11px] text-slate-400 mt-8">1 Pengajuan = 1 Hari Kerja Cuti Tahunan</p>
        </div>
        <div class="lg:col-span-6 p-8">
            <h2 class="text-xl font-extrabold text-slate-900">Masuk ke Akun Anda</h2>
            <p class="text-xs text-slate-500 mb-5">Gunakan email resmi klinik dan kata sandi Anda.</p>

            @if($errors->any())
                <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 px-4 py-2.5 text-xs font-semibold text-rose-700">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email Pengguna</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kata Sandi</label>
                    <input type="password" name="password" required class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm">
                </div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-sm">Masuk Sistem</button>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-200 space-y-2">
                <p class="text-xs font-bold text-slate-600">Akun Demo Cepat (Password: <code>password</code>):</p>
                <a href="{{ url('/login-sebagai/karyawan') }}" class="block text-center py-2 rounded-xl border border-teal-200 bg-teal-50 text-teal-800 text-xs font-bold">Masuk Cepat sebagai Staff (Budi Santoso)</a>
                <a href="{{ url('/login-sebagai/atasan') }}" class="block text-center py-2 rounded-xl border border-cyan-200 bg-cyan-50 text-cyan-800 text-xs font-bold">Masuk Cepat sebagai Atasan (dr. Andi Pratama)</a>
                <a href="{{ url('/login-sebagai/admin_hr') }}" class="block text-center py-2 rounded-xl border border-amber-200 bg-amber-50 text-amber-800 text-xs font-bold">Masuk Cepat sebagai Admin HR (Siti Rahma)</a>
            </div>
        </div>
    </div>
</body>
</html>