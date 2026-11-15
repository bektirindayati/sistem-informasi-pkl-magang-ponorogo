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
<x-statistik-magang :stat="$statistik" />


{{-- Ganti dua section lama di welcome.blade.php dengan isi file ini. --}}

{{-- ==================== INSTANSI TUJUAN ==================== --}}
@php
    $batasDinas  = 6;
    $dinasTampil = $dinases->take($batasDinas);
    $totalDinas  = $dinases->count();
@endphp

<section class="py-8 sm:py-16 px-4 sm:px-6 bg-slate-50 dark:bg-slate-800/40">
    <div class="max-w-6xl mx-auto">

        <x-section-heading
            eyebrow="Instansi Tujuan"
            title="Daftar Dinas & Instansi Kabupaten Ponorogo"
        >
            Pilih instansi penempatan magang yang tersedia.
        </x-section-heading>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-5">

            @forelse ($dinasTampil as $dinas)
                <x-instansi-card
                    :slug="Str::slug($dinas->nama_dinas)"
                    :nama="$dinas->nama_dinas"
                    :deskripsi="$dinas->deskripsi"
                    :gambar="$dinas->gambar ?? null"
                    :rekap="$dinas->rekap ?? null"
                />
            @empty
                <div class="col-span-full py-10 text-center">
                    <x-ikon name="building" class="mx-auto mb-3 h-10 w-10 text-slate-400" />
                    <h3 class="font-semibold text-slate-700 dark:text-slate-200">Belum ada instansi yang tersedia</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Silakan cek kembali nanti.</p>
                </div>
            @endforelse

        </div>

        @if ($totalDinas > $batasDinas)
            <div class="mt-8 sm:mt-10 flex flex-col items-center gap-2">
                <x-button :href="route('instansi.index')" variant="secondary" size="sm">
                    Lihat semua dinas ({{ $totalDinas }})
                </x-button>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Menampilkan {{ $dinasTampil->count() }} dari {{ $totalDinas }} dinas
                </p>
            </div>
        @endif

    </div>
</section>


{{-- ==================== DOKUMENTASI ==================== --}}
@php
    $totalDok = $totalDokumentasi ?? $dokumentasis->count();
@endphp

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
                    <x-ikon name="photo" class="mx-auto mb-3 h-10 w-10 text-slate-400" />
                    <h3 class="font-semibold text-slate-700 dark:text-slate-100">Belum ada dokumentasi kegiatan</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-300 mt-1">
                        Dokumentasi kegiatan magang akan tampil di sini setelah diunggah admin.
                    </p>
                </div>
            @endforelse

        </div>

        @if ($totalDok > $dokumentasis->count())
            <div class="mt-8 sm:mt-10 flex flex-col items-center gap-2">
                <x-button :href="route('dokumentasi.index')" variant="secondary" size="sm">
                    Lihat semua dokumentasi ({{ $totalDok }})
                </x-button>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Menampilkan {{ $dokumentasis->count() }} dari {{ $totalDok }} kegiatan
                </p>
            </div>
        @endif

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
</body>
</html>
