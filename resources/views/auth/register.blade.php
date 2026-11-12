<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Siswa - Sistem Peminjaman Alat Laboratorium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-stretch">

    <!-- Left Branding Section -->
    <div class="hidden lg:flex lg:w-5/12 bg-slate-900 flex-col justify-between p-12 text-white relative overflow-hidden">
        <div class="absolute -top-20 -left-20 w-80 h-80 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

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

        <div class="relative z-10 my-auto py-12">
            <span class="inline-block px-3 py-1 bg-blue-900/60 text-blue-400 rounded-full text-xs font-semibold mb-4 border border-blue-800">
                Pendaftaran Akun Siswa
            </span>
            <h2 class="text-3xl xl:text-4xl font-extrabold leading-tight text-white mb-3">
                Mulai Akses Peminjaman <br><span class="text-sky-400">Peralatan Laboratorium</span>
            </h2>
            <p class="text-slate-400 text-sm mb-8 leading-relaxed">
                Daftarkan akun siswa Anda untuk meminjam alat praktikum sekolah secara resmi, cepat, dan transparan.
            </p>

            <div class="p-4 bg-slate-800/80 border border-slate-700/80 rounded-xl space-y-2 text-xs text-slate-300">
                <div class="font-bold text-white text-sm">Persyaratan Registrasi:</div>
                <div class="flex items-center gap-2">- Gunakan NISN resmi yang terdaftar di sekolah</div>
                <div class="flex items-center gap-2">- Masukkan email aktif untuk notifikasi peminjaman</div>
                <div class="flex items-center gap-2">- Password minimal 6 karakter dan otomatis di-hash (Bcrypt)</div>
            </div>
        </div>

        <div class="relative z-10 text-xs text-slate-500">
            &copy; {{ date('Y') }} Laboratorium Sekolah • Standar USK Rekayasa Perangkat Lunak
        </div>
    </div>

    <!-- Right Register Form -->
    <div class="w-full lg:w-7/12 flex flex-col justify-center items-center p-6 sm:p-12 overflow-y-auto">
        <div class="w-full max-w-md bg-white p-8 sm:p-10 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200/80 my-auto">
            
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Registrasi Siswa Baru</h2>
                <p class="text-xs text-slate-500 mt-1">Lengkapi formulir di bawah ini untuk membuat akun peminjam</p>
            </div>

            <!-- Tab Switcher -->
            <div class="grid grid-cols-2 p-1 bg-slate-100 rounded-xl mb-6 text-sm font-semibold">
                <a href="{{ route('login') }}" class="py-2 text-center rounded-lg text-slate-500 hover:text-slate-800 transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="py-2 text-center rounded-lg bg-white text-slate-900 shadow-sm transition">
                    Registrasi Siswa
                </a>
            </div>

            @if(isset($errors) && $errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-xs font-medium space-y-1">
                    @foreach($errors->all() as $err)
                        <div>• {{ $err }}</div>
                    @endforeach
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('register.post') }}" method="POST" class="space-y-3.5">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Siswa</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Ahmad Fadhil" required
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                <!-- NISN & Kelas (2 Columns) -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">NISN Siswa</label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="Contoh: 2104018" required
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kelas & Jurusan</label>
                        <input type="text" name="kelas" value="{{ old('kelas') }}" placeholder="Contoh: X RPL 1" required
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: ahmad@smk.sch.id" required
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                <!-- Kata Sandi & Konfirmasi (2 Columns) -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi</label>
                        <input type="password" name="password" placeholder="Min. 6 Karakter" required
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Ulangi Sandi</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi Sandi" required
                            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-lg text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>
                </div>


                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition mt-2">
                    Daftar Akun Siswa Sekarang
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-slate-600">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700 hover:underline">
                    Masuk di sini
                </a>
            </div>

        </div>
    </div>

</body>
</html>
