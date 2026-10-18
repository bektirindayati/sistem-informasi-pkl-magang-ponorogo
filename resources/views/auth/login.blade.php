<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - MagangHub Portal Pendaftaran Magang Terpadu</title>

    {{-- Tailwind + tema + config dark mode --}}
    @include('partials.tailwind-tema')

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    <!-- Tombol mode gelap / terang -->
    <div class="fixed top-4 right-4 z-50">
        <x-theme-toggle />
    </div>

    <!-- Ambient Light Background (Biru & Cyan khas MagangHub) -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px]
                bg-gradient-to-tr from-blue-200/50 via-cyan-200/40 to-sky-200/50
                dark:from-blue-500/20 dark:via-cyan-500/15 dark:to-sky-500/20
                rounded-full blur-[120px] pointer-events-none"></div>

    <!-- CARD CONTAINER LOGIN -->
    <div class="w-full max-w-md bg-white/90 dark:bg-slate-900/90 backdrop-blur-md p-8 rounded-3xl shadow-xl dark:shadow-none border border-slate-200/80 dark:border-slate-700/80 relative z-10 flex flex-col items-center text-center">

        <!-- LOGO & JUDUL -->
        <div class="text-center mb-6 flex flex-col items-center">
            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-600 to-cyan-600 flex items-center justify-center text-white font-black text-3xl shadow-md mb-4">
                M
            </div>

            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Masuk ke MagangHub</h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Portal Pendaftaran Magang dan PKL Terpadu Kabupaten Ponorogo
            </p>
        </div>

        <!-- Alert Notifikasi Jika Error -->
        @if(session('error'))
            <div class="w-full mb-5 p-3 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-300 text-xs font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <!-- DIVIDER / GARIS PEMBATAS -->
        <div class="w-full mb-6 flex items-center gap-3">
            <div class="h-[1px] w-full bg-slate-200 dark:bg-slate-700"></div>
            <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider whitespace-nowrap">Akses Masuk</span>
            <div class="h-[1px] w-full bg-slate-200 dark:bg-slate-700"></div>
        </div>

        <!-- TOMBOL UTAMA: LOGIN WITH GOOGLE -->
        <a href="{{ route('auth.google') }}"
           class="w-full flex items-center justify-center gap-3 px-6 py-3.5
                  border border-slate-200 dark:border-slate-600 rounded-2xl
                  bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-100
                  font-bold text-sm shadow-sm
                  hover:bg-slate-50 dark:hover:bg-slate-700 hover:border-slate-300 dark:hover:border-slate-500
                  hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
            <svg class="w-5 h-5 transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span>Masuk dengan Akun Google</span>
        </a>

        <!-- FOOTER INFO -->
        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-8 font-medium leading-normal">
            Gunakan akun Google aktif untuk pendaftaran magang mandiri maupun PKL instansi.
        </p>

        <a href="/" class="mt-4 text-xs font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Beranda</span>
        </a>

    </div>

</body>
</html>