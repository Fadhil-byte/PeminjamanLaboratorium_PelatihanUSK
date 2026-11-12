<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Peminjaman Alat Laboratorium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-stretch">

    <!-- Left Branding Section (Identik dengan Desain Figma Mockup 1) -->
    <div class="hidden lg:flex lg:w-5/12 bg-slate-900 flex-col justify-between p-12 text-white relative overflow-hidden">
        <!-- Glow circles -->
        <div class="absolute -top-20 -left-20 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Brand Header -->
        <div class="relative z-10 flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight">LabInstrument</h1>
                <p class="text-[11px] font-semibold text-slate-400 tracking-wider">SISTEM INVENTARIS & PEMINJAMAN</p>
            </div>
        </div>

        <!-- Main Copy & Key Points -->
        <div class="relative z-10 my-auto py-12">
            <span class="inline-block px-3 py-1 bg-blue-900/60 text-blue-400 rounded-full text-xs font-semibold mb-4 border border-blue-800">
                Uji Sertifikasi Kompetensi (USK)
            </span>
            <h2 class="text-3xl xl:text-4xl font-extrabold leading-tight text-white mb-3">
                Sistem Peminjaman <br><span class="text-sky-400">Alat Laboratorium</span>
            </h2>
            <p class="text-slate-400 text-sm mb-8 leading-relaxed">
                Kelola dan ajukan peminjaman alat lab sekolah dengan mudah, terdata rapi, dan terpantau secara real-time.
            </p>

            <!-- Feature Checklist -->
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-emerald-400 shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-100">Katalog Alat & Cek Stok Real-Time</h4>
                        <p class="text-xs text-slate-400">Ketersediaan alat terpantau otomatis tanpa minus</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-emerald-400 shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-100">Validasi Peminjaman Terstruktur</h4>
                        <p class="text-xs text-slate-400">Tanggal kembali terkontrol otomatis sesuai jadwal</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-7 h-7 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-emerald-400 shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-100">Keamanan Akun Berstandar Hash</h4>
                        <p class="text-xs text-slate-400">Password tersimpan dengan enkripsi bcrypt aman</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="relative z-10 text-xs text-slate-500">
            &copy; {{ date('Y') }} Laboratorium Sekolah • Standar USK Rekayasa Perangkat Lunak
        </div>
    </div>

    <!-- Right Login Form Section -->
    <div class="w-full lg:w-7/12 flex flex-col justify-center items-center p-6 sm:p-12">
        <div class="w-full max-w-md bg-white p-8 sm:p-10 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200/80">
            
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Masuk ke Akun</h2>
                <p class="text-xs text-slate-500 mt-1">Silakan isi kredensial untuk mengakses sistem laboratorium</p>
            </div>

            <!-- Tab Switcher (Masuk vs Registrasi) -->
            <div class="grid grid-cols-2 p-1 bg-slate-100 rounded-xl mb-6 text-sm font-semibold">
                <a href="{{ route('login') }}" class="py-2 text-center rounded-lg bg-white text-slate-900 shadow-sm transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="py-2 text-center rounded-lg text-slate-500 hover:text-slate-800 transition">
                    Registrasi Siswa
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-xs font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-xs font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input NISN / Email -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">NISN atau Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input type="text" name="login" value="{{ old('login') }}" placeholder="Contoh: 2104018 atau ahmad@smk.sch.id" required autofocus
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                </div>

                <!-- Input Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-slate-700">Kata Sandi</label>
                        <span class="text-[11px] text-blue-600 font-semibold cursor-pointer hover:underline">Lupa sandi?</span>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input type="password" name="password" placeholder="••••••••••••" required
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                    <label for="remember" class="ml-2 block text-xs text-slate-600 cursor-pointer">
                        Ingat kredensial di perangkat ini
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition flex items-center justify-center gap-2">
                    <span>Masuk ke Sistem</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>


            <!-- Footer Register Link -->
            <div class="mt-6 text-center text-xs text-slate-600">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline">
                    Daftar Siswa Sekarang
                </a>
            </div>

        </div>
    </div>

</body>
</html>
