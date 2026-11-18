<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CV. GARUDA RAJA PERKASA DUABELAS - Sistem Keuangan</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 text-slate-100 min-h-screen flex flex-col justify-between font-sans antialiased">
    <!-- Top Header -->
    <header class="max-w-7xl mx-auto w-full px-6 py-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-amber-600 via-amber-500 to-yellow-400 text-slate-950 flex items-center justify-center font-bold shadow-md shadow-amber-500/20">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L9.5 7H14.5L12 2Z" />
                    <path d="M4 8L10 10L6 14L2 11L4 8Z" opacity="0.9" />
                    <path d="M20 8L14 10L18 14L22 11L20 8Z" opacity="0.9" />
                    <path d="M7 13L12 11L17 13L19 19L12 22L5 19L7 13Z" />
                </svg>
            </div>
            <div>
                <span class="block text-sm font-black tracking-wider text-amber-400">CV. GARUDA RAJA PERKASA</span>
                <span class="block text-[10px] tracking-widest text-slate-400 font-bold uppercase">DUABELAS</span>
            </div>
        </div>

        <div>
            @auth
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-extrabold text-xs shadow-md transition">
                    Buka Dashboard &rarr;
                </a>
            @else
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-amber-400 border border-amber-500/30 font-extrabold text-xs shadow-md transition">
                    Masuk ke Sistem &rarr;
                </a>
            @endauth
        </div>
    </header>

    <!-- Hero Content -->
    <main class="max-w-5xl mx-auto px-6 py-12 text-center space-y-8 my-auto">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 text-xs font-bold">
            <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
            Aplikasi Pencatatan & Pelaporan Keuangan Perusahaan
        </div>

        <h1 class="text-3xl sm:text-5xl md:text-6xl font-black text-white tracking-tight leading-tight max-w-4xl mx-auto">
            Sistem Informasi Keuangan & Pembukuan Kas Terpadu
        </h1>

        <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto leading-relaxed">
            Pencatatan mutasi kas pemasukan (BKM) dan pengeluaran (BKK), rekapitulasi arus kas 12 bulan, laporan laba rugi, serta ekspor resmi PDF ber-kop surat dan Excel.
        </p>

        <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
            @auth
                <a href="{{ route('dashboard') }}" class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-sm shadow-xl shadow-amber-500/20 transition">
                    Buka Dashboard Keuangan
                </a>
            @else
                <a href="{{ route('login') }}" class="px-7 py-3.5 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-400 hover:to-amber-500 text-slate-950 font-black text-sm shadow-xl shadow-amber-500/20 transition">
                    Masuk ke Akun Sekarang
                </a>
            @endauth
        </div>

        <!-- Corporate Info Box -->
        <div class="pt-8 max-w-3xl mx-auto">
            <div class="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md text-xs text-slate-300 space-y-2">
                <div class="font-bold text-amber-400 text-sm">IDENTITAS PERUSAHAAN</div>
                <div class="font-extrabold text-white text-base">CV. GARUDA RAJA PERKASA DUABELAS</div>
                <div class="text-slate-300">
                    Jl. Trans Kalimantan Ruko Keling Kumang No.2, Simpang Ampar, Kec. Tayan Hilir, Kabupaten Sanggau, Kalimantan Barat 78564
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="max-w-7xl mx-auto w-full px-6 py-6 text-center text-xs text-slate-500 border-t border-slate-800/60">
        &copy; {{ date('Y') }} CV. GARUDA RAJA PERKASA DUABELAS. Hak Cipta Dilindungi.
    </footer>
</body>
</html>
