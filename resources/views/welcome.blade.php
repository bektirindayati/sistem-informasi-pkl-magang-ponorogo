<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SIMANTARA - Sistem Informasi Pendaftaran Magang Terintegrasi</title>

    {{-- 1) Tema: HARUS sebelum Tailwind (cegah layar berkedip + definisi toggleTheme) --}}
    @include('partials.theme-script')

    {{-- 2) Tailwind + config dark mode (config WAJIB tepat setelah script CDN, di <head>) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased selection:bg-blue-600 selection:text-white overflow-x-hidden">

    <x-preloader />
    <x-navbar />
    <x-ai-chatbot-modal />

{{-- ==================== HERO / BERANDA ==================== --}}
<section id="beranda"
    class="relative min-h-[calc(100vh-1rem)] flex flex-col justify-center
           pt-28 pb-16 sm:pt-32 sm:pb-20 px-4 sm:px-6
           overflow-hidden bg-sky-100 dark:bg-[#0b1a33]">

    {{-- Tekstur titik halus --}}
    <div class="absolute inset-0 pointer-events-none opacity-[0.35] dark:opacity-[0.12]
                [background-image:radial-gradient(#94a3b8_1px,transparent_1px)]
                [background-size:24px_24px]
                [mask-image:radial-gradient(ellipse_at_center,black_30%,transparent_75%)]">
    </div>

    <div class="relative z-10 max-w-4xl mx-auto text-center flex flex-col items-center">

        {{-- BADGE --}}
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full
                    bg-white/90 dark:bg-slate-800/80
                    border border-white dark:border-slate-700
                    shadow-md shadow-blue-100/60 dark:shadow-none backdrop-blur-md mb-8 sm:mb-10">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-60"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-500"></span>
            </span>
            <span class="text-xs sm:text-sm font-bold text-slate-700 dark:text-slate-200 tracking-wide">
                Pusat Informasi & Pendaftaran Magang
            </span>
        </div>

        {{-- JUDUL --}}
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-[1.12]">
            Portal Magang Terpadu
            <br>
            <span class="bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 bg-clip-text text-transparent">
                Kabupaten Ponorogo
            </span>
        </h1>

        {{-- DESKRIPSI --}}
        <p class="mt-6 sm:mt-7 text-sm sm:text-lg text-slate-600 dark:text-slate-300 max-w-2xl font-normal leading-relaxed">
            Platform resmi pengajuan Praktik Kerja Lapangan (PKL) / Magang
            ke berbagai instansi daerah. Kenali bidang penempatan dan
            daftarkan dirimu segera.
        </p>

        {{-- BUTTON --}}
        <div class="mt-10 sm:mt-12 flex flex-row gap-2.5 sm:gap-4 w-full sm:w-auto justify-center">

            <a href="{{ route('user.pendaftaran.create') }}"
               class="flex-1 sm:flex-none px-3.5 sm:px-8 py-3.5 sm:py-4
                      bg-gradient-to-r from-blue-600 to-cyan-600
                      hover:from-blue-700 hover:to-cyan-700
                      text-white font-bold text-xs sm:text-sm rounded-xl
                      shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30
                      hover:-translate-y-0.5 transition-all duration-200
                      flex items-center justify-center gap-1.5 sm:gap-2 whitespace-nowrap">
                <span>Daftar Magang Sekarang</span>
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>

            <a href="{{ route('user.pendaftaran.status') }}"
               class="flex-1 sm:flex-none px-3.5 sm:px-8 py-3.5 sm:py-4
                      bg-white text-slate-700 hover:bg-slate-50 hover:border-slate-300
                      dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700 dark:hover:border-slate-600
                      font-bold text-xs sm:text-sm rounded-xl
                      border border-slate-200 dark:border-slate-700 shadow-sm
                      hover:-translate-y-0.5 transition-all duration-200
                      flex items-center justify-center whitespace-nowrap">
                Cek Status Pengajuan
            </a>

        </div>

        {{-- INFORMASI SINGKAT --}}
        <div class="mt-8 sm:mt-10 flex flex-wrap justify-center items-center gap-x-5 sm:gap-x-7 gap-y-3 text-xs sm:text-sm text-slate-500 dark:text-slate-400">

            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Pendaftaran online</span>
            </div>

            <span class="hidden sm:block w-1 h-1 rounded-full bg-cyan-300 dark:bg-cyan-600"></span>

            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622C17.176 19.29 21 14.591 21 9c0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span>Informasi terpusat</span>
            </div>

            <span class="hidden sm:block w-1 h-1 rounded-full bg-cyan-300 dark:bg-cyan-600"></span>

            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V9h4v12M15 21V9h4v12M7 9l5-6 5 6"></path>
                </svg>
                <span>Berbagai perangkat daerah</span>
            </div>

        </div>

        {{-- SCROLL INDICATOR --}}
        <div class="mt-14 sm:mt-16 flex flex-col items-center text-slate-400 dark:text-slate-500">
            <span class="text-[10px] sm:text-xs font-semibold tracking-[0.18em] uppercase">
                Jelajahi informasi magang
            </span>
            <div class="mt-3 w-7 h-10 rounded-full border border-slate-300 dark:border-slate-600 bg-white/50 dark:bg-slate-800/50 flex justify-center pt-2 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-bounce"></span>
            </div>
        </div>

    </div>

    {{-- FADE PENDEK hero -> section statistik (Opsi 2) --}}
    <div class="absolute bottom-0 left-0 right-0 h-24 sm:h-32
                bg-gradient-to-b from-sky-100 to-white
                dark:from-[#0b1a33] dark:to-slate-900
                pointer-events-none"></div>

</section>


{{-- ==================== BAGIAN BAWAH HERO ==================== --}}
<div class="bg-white dark:bg-slate-900">

    {{-- ==================== STATISTIK ==================== --}}
    <section class="py-8 sm:py-16 px-4 sm:px-6 bg-white dark:bg-slate-900">
        <div class="max-w-6xl mx-auto">

            <x-section-heading
                eyebrow="Statistik Magang / PKL"
                title="Statistik Magang / PKL"
            >
                Data pendaftaran dan pemagang pada sistem SiMagang
            </x-section-heading>

            {{-- KARTU STATISTIK --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">

                {{-- TOTAL PENDAFTAR --}}
                <div class="group relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl
                            border border-slate-200 dark:border-slate-700
                            shadow-[0_4px_20px_rgba(15,23,42,0.04)] dark:shadow-none
                            hover:shadow-[0_10px_30px_rgba(47,91,255,0.12)]
                            hover:-translate-y-0.5 transition-all duration-200 p-5">

                    <svg class="absolute -right-4 -bottom-4 w-28 h-28 text-[#2F5BFF]/[0.06] dark:text-blue-400/[0.08] pointer-events-none"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-width="1.2" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4" stroke-width="1.2"/>
                        <path stroke-width="1.2" d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>

                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#2F5BFF]"></div>

                    <div class="relative flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-[#2F5BFF]/10 border border-[#2F5BFF]/15 dark:border-blue-400/20 flex items-center justify-center shrink-0 group-hover:bg-[#2F5BFF]/15 transition-colors">
                            <svg class="w-5 h-5 text-[#2F5BFF] dark:text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
                                <circle cx="9" cy="7" r="4" stroke-width="1.8"/>
                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-100">Total Pendaftar</p>
                            <p class="text-xs text-slate-400 mt-0.5">Seluruh pengajuan</p>
                        </div>
                    </div>

                    <div class="relative mt-4 flex items-baseline gap-2">
                        <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white leading-none tracking-tight">{{ $totalPendaftar }}</h3>
                        <span class="text-xs font-semibold text-slate-400">pengajuan</span>
                    </div>
                    <p class="relative text-xs text-slate-400 mt-1.5">Tercatat di sistem</p>
                </div>

                {{-- PEMAGANG AKTIF --}}
                <div class="group relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl
                            border border-slate-200 dark:border-slate-700
                            shadow-[0_4px_20px_rgba(15,23,42,0.04)] dark:shadow-none
                            hover:shadow-[0_10px_30px_rgba(16,185,129,0.12)]
                            hover:-translate-y-0.5 transition-all duration-200 p-5">

                    <svg class="absolute -right-4 -bottom-4 w-28 h-28 text-emerald-500/[0.07] dark:text-emerald-400/[0.08] pointer-events-none"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="9" cy="7" r="4" stroke-width="1.2"/>
                        <path stroke-width="1.2" d="M3 21v-2a6 6 0 0112 0v2"/>
                        <path stroke-width="1.4" d="M16 11l2 2 4-5"/>
                    </svg>

                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>

                    <div class="relative flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 flex items-center justify-center shrink-0 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-500/20 transition-colors">
                            <svg class="w-5 h-5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="9" cy="7" r="4" stroke-width="1.8"/>
                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M3 21v-2a6 6 0 0112 0v2"/>
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M16 11l2 2 4-5"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-100">Pemagang Aktif</p>
                            <p class="text-xs text-slate-400 mt-0.5">Sedang menjalani magang</p>
                        </div>
                    </div>

                    <div class="relative mt-4 flex items-baseline gap-2">
                        <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white leading-none tracking-tight">{{ $pemagangAktif }}</h3>
                        <span class="text-xs font-semibold text-slate-400">orang</span>
                    </div>
                    <p class="relative text-xs text-slate-400 mt-1.5">Status diterima & periode masih berjalan</p>
                </div>

                {{-- ALUMNI --}}
                <div class="group relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl
                            border border-slate-200 dark:border-slate-700
                            shadow-[0_4px_20px_rgba(15,23,42,0.04)] dark:shadow-none
                            hover:shadow-[0_10px_30px_rgba(71,85,105,0.12)]
                            hover:-translate-y-0.5 transition-all duration-200 p-5">

                    <svg class="absolute -right-4 -bottom-4 w-28 h-28 text-slate-500/[0.07] dark:text-slate-400/[0.08] pointer-events-none"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" d="M22 10L12 5 2 10l10 5 10-5z"/>
                        <path stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" d="M6 12v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5"/>
                    </svg>

                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-slate-500"></div>

                    <div class="relative flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-slate-100 dark:bg-slate-700 border border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 group-hover:bg-slate-200 dark:group-hover:bg-slate-600 transition-colors">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M22 10L12 5 2 10l10 5 10-5z"/>
                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M6 12v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-100">Alumni Magang</p>
                            <p class="text-xs text-slate-400 mt-0.5">Telah selesai periode</p>
                        </div>
                    </div>

                    <div class="relative mt-4 flex items-baseline gap-2">
                        <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white leading-none tracking-tight">{{ $alumniSelesai }}</h3>
                        <span class="text-xs font-semibold text-slate-400">orang</span>
                    </div>
                    <p class="relative text-xs text-slate-400 mt-1.5">Riwayat pemagang yang sudah tuntas</p>
                </div>

            </div>

            {{-- GRAFIK --}}
            <div class="relative w-full min-w-0 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-[0_4px_20px_rgba(15,23,42,0.04)] dark:shadow-none overflow-hidden">

                {{-- HEADER --}}
                <div class="px-6 pt-5 pb-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-slate-100 dark:border-slate-700">

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#2F5BFF]/10 border border-[#2F5BFF]/15 dark:border-blue-400/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#2F5BFF] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 20h17M8 17v-5m4 5V8m4 9V5m4 12V3"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Perkembangan Pendaftar & Pemagang</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbandingan jumlah pendaftar dan pemagang aktif setiap tahun</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-300 w-fit">
                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/>
                            <path d="M16 2v4M8 2v4M3 10h18" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <span class="font-medium">5 Tahun Terakhir</span>
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M6 9l6 6 6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                {{-- GRAFIK + RINGKASAN --}}
                <div class="px-5 pb-5 pt-4">

                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 min-w-0 items-stretch">

                        <div class="lg:col-span-3 rounded-xl bg-slate-50/70 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 p-4">
                            <div class="relative h-[300px]">
                                <canvas id="statistikMagangChart"></canvas>
                            </div>
                        </div>

                        <div class="relative overflow-hidden rounded-xl bg-[#2F5BFF]/[0.04] dark:bg-blue-500/10 border border-[#2F5BFF]/10 dark:border-blue-400/15 p-5">

                            <h4 class="text-base font-bold text-[#2F5BFF] dark:text-blue-400 mb-5">Ringkasan Tahun {{ $tahunSekarang }}</h4>

                            <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-700">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-full bg-[#6385F5]"></span>
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Pendaftar</span>
                                </div>
                                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $pendaftarTahunIni }}</span>
                            </div>

                            <div class="flex items-center justify-between py-4 border-b border-slate-200 dark:border-slate-700">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3 h-3 rounded-full bg-[#F45B91]"></span>
                                    <span class="text-sm text-slate-600 dark:text-slate-300">Diterima</span>
                                </div>
                                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $diterimaTahunIni }}</span>
                            </div>

                            <div class="mt-5">
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4">Tingkat Diterima</p>

                                <div class="flex items-center gap-3">
                                    <div class="relative w-20 h-20 shrink-0">
                                        <svg class="w-20 h-20" viewBox="0 0 100 100">
                                            <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="9"
                                                    class="text-slate-200 dark:text-slate-700"/>
                                            <circle cx="50" cy="50" r="40" fill="none"
                                                    stroke="#ec4899" stroke-width="9"
                                                    stroke-linecap="round"
                                                    stroke-dasharray="251.2"
                                                    stroke-dashoffset="{{ 251.2 - (251.2 * $persentaseDiterima / 100) }}"
                                                    transform="rotate(-90 50 50)"/>
                                        </svg>
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <span class="text-sm font-bold text-pink-500">{{ $persentaseDiterima }}%</span>
                                        </div>
                                    </div>
                                    <p class="text-xs leading-5 text-slate-500 dark:text-slate-400">
                                        {{ $diterimaTahunIni }} dari {{ $pendaftarTahunIni }} pendaftar tahun ini telah diterima.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- INFO --}}
                    <div class="mt-4 px-4 py-3 rounded-xl bg-[#2F5BFF]/[0.05] dark:bg-blue-500/10 border border-[#2F5BFF]/10 dark:border-blue-400/15 flex items-center gap-3">
                        <div class="w-7 h-7 rounded-full bg-[#2F5BFF]/10 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-[#2F5BFF] dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z"/>
                            </svg>
                        </div>
                        <p class="text-xs text-[#2F5BFF] dark:text-blue-300">Data diperbarui secara real-time dari sistem SiMagang.</p>
                    </div>

                </div>
            </div>

        </div>
    </section>


    {{-- ==================== INSTANSI TUJUAN ==================== --}}
    <section class="py-8 sm:py-16 px-4 sm:px-6 bg-slate-50 dark:bg-slate-800/40">
        <div class="max-w-6xl mx-auto">

            <x-section-heading
                eyebrow="Instansi Tujuan"
                title="Daftar Dinas & Instansi Kabupaten Ponorogo"
            >
                Pilih instansi penempatan magang yang tersedia.
            </x-section-heading>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-5">

                @forelse ($dinases as $dinas)
                    <x-instansi-card
                        :slug="Str::slug($dinas->nama_dinas)"
                        :nama="$dinas->nama_dinas"
                        :deskripsi="$dinas->deskripsi"
                        :icon="'🏢'"
                        :gambar="$dinas->gambar ?? null"
                        :rekap="$dinas->rekap ?? null"
                    />
                @empty
                    <div class="col-span-full py-10 text-center">
                        <div class="text-4xl mb-3">🏢</div>
                        <h3 class="font-semibold text-slate-700 dark:text-slate-200">Belum ada instansi yang tersedia</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Silakan cek kembali nanti.</p>
                    </div>
                @endforelse

            </div>

        </div>
    </section>


   {{-- ==================== DOKUMENTASI ==================== --}}
<section id="dokumentasi" class="py-8 sm:py-16 px-4 sm:px-6 bg-white dark:bg-slate-950">
    <div class="max-w-6xl mx-auto">

        <x-section-heading
            eyebrow="Galeri & Kegiatan"
            title="Dokumentasi Aktivitas Magang"
        >
            Intip keseruan suasana kerja, kolaborasi proyek, dan bimbingan langsung bersama mentor profesional.
        </x-section-heading>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">

            @forelse($dokumentasis as $item)
                <a href="{{ route('dokumentasi.show', $item) }}"
                   class="block h-full rounded-2xl focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500
                          focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-950">
                    <x-galeri-card
                        :tag="$item->kategori_badge"
                        :judul="$item->judul_kegiatan"
                        :gambar="$item->sampul_url"
                        :tempat="$item->tempat_pelaksanaan"
                        :tanggal="$item->tanggal_pelaksanaan"
                        :ringkasan="$item->ringkasan"
                    />
                </a>
            @empty
                <div class="col-span-full py-10 text-center">
                    <div class="text-4xl mb-3">📸</div>
                    <h3 class="font-semibold text-slate-700 dark:text-slate-100">Belum ada dokumentasi kegiatan</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-300 mt-1">
                        Dokumentasi kegiatan magang akan tampil di sini setelah diunggah admin.
                    </p>
                </div>
            @endforelse

        </div>
    </div>
</section>

{{-- ==================== CTA ==================== --}}
<div class="px-4 sm:px-6">
    <div class="max-w-6xl mx-auto mt-4 sm:mt-8 mb-12 sm:mb-16
                bg-gradient-to-r from-blue-600 via-blue-700 to-cyan-700
                dark:from-slate-900 dark:via-blue-950 dark:to-slate-900
                border border-blue-200/50 dark:border-slate-700
                rounded-3xl p-6 sm:p-12 text-center text-white
                shadow-xl relative overflow-hidden">

        <div class="absolute inset-0
                    bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.18),transparent_50%)]
                    dark:bg-[radial-gradient(circle_at_top_right,rgba(59,130,246,0.2),transparent_50%)]
                    pointer-events-none"></div>

        <h3 class="text-xl sm:text-3xl font-extrabold relative z-10">
            Tertarik Bergabung Menjadi Bagian dari Kami?
        </h3>

        <p class="text-blue-50 dark:text-slate-300
                  text-xs sm:text-base mt-3 max-w-xl mx-auto relative z-10">
            Segera siapkan berkas pengajuan surat pengantar dari kampusmu
            dan pilih instansi tujuanmu sekarang juga.
        </p>

        <div class="mt-6 sm:mt-8 relative z-10">
            <a href="{{ route('user.pendaftaran.create') }}"
               class="inline-flex items-center gap-2 px-6 sm:px-8 py-3.5 sm:py-4
                      bg-white text-blue-700 hover:bg-blue-50
                      dark:bg-blue-600 dark:text-white dark:hover:bg-blue-500
                      font-bold text-xs sm:text-sm rounded-xl shadow-lg
                      transition duration-200">
                <span>Mulai Ajukan Pendaftaran</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>
                </svg>
            </a>
        </div>

    </div>
</div>

</div>{{-- /bg-white --}}

<x-footer />

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const statistik = @json($statistikMagang);
    const tahun = statistik.map(item => item.tahun);
    const pendaftar = statistik.map(item => Number(item.pendaftar));
    const diterima = statistik.map(item => Number(item.diterima));
    const ctx = document.getElementById('statistikMagangChart');

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: tahun,
            datasets: [
                { label: 'Pendaftar', data: pendaftar, backgroundColor: '#6385F5',
                  borderRadius: 7, borderSkipped: false, barPercentage: 0.65, categoryPercentage: 0.65 },
                { label: 'Diterima', data: diterima, backgroundColor: '#F45B91',
                  borderRadius: 7, borderSkipped: false, barPercentage: 0.65, categoryPercentage: 0.65 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'top',
                    labels: { usePointStyle: true, pointStyle: 'rectRounded', padding: 25, font: { size: 13 } }
                },
                tooltip: {
                    backgroundColor: '#172554', titleColor: '#ffffff', bodyColor: '#ffffff',
                    padding: 12, cornerRadius: 10, displayColors: true
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 13 } } },
                y: { beginAtZero: true, ticks: { precision: 0, font: { size: 12 } }, grid: { borderDash: [5, 5] } }
            }
        }
    });

    // Warna grafik mengikuti mode terang/gelap
    function terapkanTemaChart(dark) {
        chart.options.plugins.legend.labels.color = dark ? '#cbd5e1' : '#475569';
        chart.options.scales.x.ticks.color = dark ? '#cbd5e1' : '#475569';
        chart.options.scales.y.ticks.color = dark ? '#94a3b8' : '#64748b';
        chart.options.scales.y.grid.color = dark ? '#334155' : '#e2e8f0';
        chart.update();
    }
    terapkanTemaChart(document.documentElement.classList.contains('dark'));
    window.addEventListener('themechange', e => terapkanTemaChart(e.detail.dark));

</script>
</body>
</html>