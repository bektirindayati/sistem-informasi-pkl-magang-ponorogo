<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MagangHub - Portal Pendaftaran Magang Terpadu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-blue-600 selection:text-white overflow-x-hidden">

    <x-preloader />
    <x-navbar />

    {{-- ==================== HERO / BERANDA ====================
         Tetap punya blob warna sendiri di tengah, TIDAK ikut dibungkus
         <x-flowing-bg> supaya area hero tetap jadi pusat perhatian.
         Warna akhir (to-blue-50, solid) sengaja disamakan persis dengan
         warna awal <x-flowing-bg> supaya tidak ada garis sambungan. --}}
   <section id="beranda"
    class="relative min-h-screen flex flex-col justify-center
           pt-24 pb-8 sm:pt-28 sm:pb-12 px-4 sm:px-6
           overflow-hidden
           bg-gradient-to-b from-slate-50 via-slate-50 to-blue-50">

    {{-- Background blob --}}
    <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2
                w-[600px] h-[600px]
                bg-gradient-to-tr from-blue-400/20 via-cyan-400/20 to-sky-300/20
                rounded-full blur-[120px] pointer-events-none">
    </div>

    {{-- Grid background --}}
    <div class="absolute inset-0
                bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),
                linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)]
                bg-[size:3.5rem_3.5rem]
                pointer-events-none opacity-50
                [mask-image:linear-gradient(to_bottom,black_35%,transparent_95%)]
                [-webkit-mask-image:linear-gradient(to_bottom,black_35%,transparent_95%)]">
    </div>


    {{-- CONTENT --}}
    <div class="max-w-4xl mx-auto text-center flex flex-col items-center relative z-10">

        {{-- BADGE --}}
        <div class="inline-flex items-center gap-2
                    px-3.5 py-1.5
                    rounded-full
                    bg-white/90 border border-slate-200
                    shadow-sm backdrop-blur-md
                    mb-10 sm:mb-12">

            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full
                             rounded-full bg-blue-400 opacity-75">
                </span>

                <span class="relative inline-flex rounded-full
                             h-2.5 w-2.5 bg-blue-500">
                </span>
            </span>

            <span class="text-xs font-bold text-slate-700 tracking-wide">
                Pusat Informasi & Pendaftaran Magang
            </span>

        </div>


        {{-- JUDUL --}}
        <h1 class="text-3xl sm:text-5xl md:text-6xl
                   font-extrabold text-slate-900
                   tracking-tight leading-[1.18]">

            Portal Magang Terpadu <br>

            <span class="bg-gradient-to-r from-blue-700 via-cyan-600 to-sky-500
                         bg-clip-text text-transparent">
                Kabupaten Ponorogo
            </span>

        </h1>


        {{-- DESKRIPSI --}}
        <p class="mt-6 sm:mt-7
                  text-sm sm:text-lg
                  text-slate-600
                  max-w-2xl
                  font-normal
                  leading-relaxed">

            Platform resmi pengajuan Praktik Kerja Lapangan (PKL) / Magang
            ke berbagai instansi daerah. Kenali bidang penempatan dan
            daftarkan dirimu segera.

        </p>


        {{-- BUTTON --}}
        <div class="mt-16 sm:mt-17
                    flex flex-col sm:flex-row
                    gap-3 sm:gap-4
                    w-full sm:w-auto
                    justify-center">

            <a href="{{ route('user.pendaftaran.create') }}"
                class="w-full sm:w-auto
                       px-6 sm:px-8
                       py-3.5 sm:py-4
                       bg-gradient-to-r from-blue-600 to-cyan-600
                       hover:from-blue-700 hover:to-cyan-700
                       text-white font-bold text-xs sm:text-sm
                       rounded-xl
                       shadow-lg shadow-blue-500/25
                       transition-all duration-200
                       flex items-center justify-center gap-2">

                <span>Daftar Magang Sekarang</span>

                <svg class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M14 5l7 7m0 0l-7 7m7-7H3">
                    </path>

                </svg>

            </a>


            <a href="{{ route('user.pendaftaran.status') }}"
                class="w-full sm:w-auto
                       px-6 sm:px-8
                       py-3.5 sm:py-4
                       bg-white
                       text-slate-700
                       font-bold text-xs sm:text-sm
                       rounded-xl
                       border border-slate-200
                       shadow-sm
                       hover:bg-slate-100
                       transition-all duration-200
                       flex items-center justify-center">

                Cek Status Pengajuan

            </a>

        </div>

    </div>

</section>

    {{-- ====================================================================
         DARI SINI SAMPAI SEBELUM FOOTER: satu latar belakang yang mengalir
         (lihat resources/views/components/flowing-bg.blade.php). Section
         di dalamnya (Statistik, Instansi, Dokumentasi) dibuat bg-transparent
         dan ukurannya (max-width, padding vertikal) diseragamkan supaya
         tidak lagi kelihatan seperti blok-blok terpisah.
         ==================================================================== --}}
    <x-flowing-bg>

        {{-- STATISTIK MAGANG / PKL --}}
        <section class="py-8 sm:py-16 bg-transparent">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">

                <x-section-heading
                    eyebrow="Statistik Magang / PKL"
                    title="Statistik Magang / PKL"
                >
                    Data pendaftaran dan pemagang pada sistem SiMagang
                </x-section-heading>

                {{-- KARTU STATISTIK --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">

                    {{-- TOTAL PENDAFTAR --}}
                    <div class="relative overflow-hidden bg-white rounded-xl border border-slate-100 shadow-sm">

                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500"></div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 px-5 py-4">

                            <div class="flex items-center gap-3 sm:contents">
                                {{-- Icon --}}
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/>
                                        <circle cx="9" cy="7" r="4" stroke-width="1.8"/>
                                        <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                                    </svg>
                                </div>

                                {{-- Judul --}}
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-800">Total Pendaftar</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Seluruh pengajuan</p>
                                </div>
                            </div>

                            {{-- Nilai --}}
                            <div class="hidden sm:block h-9 w-px bg-slate-100"></div>

                            <div class="min-w-0">
                                <div class="flex items-baseline gap-2">
                                    <h3 class="text-2xl font-bold text-slate-800 leading-none">{{ $totalPendaftar }}</h3>
                                    <span class="text-xs text-slate-400">pengajuan</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Tercatat di sistem</p>
                            </div>

                            {{-- Indikator (dekoratif, disembunyikan di HP supaya tidak sesak) --}}
                            <div class="hidden sm:flex sm:ml-auto items-end gap-1 h-7 shrink-0">
                                <span class="w-1.5 h-2 rounded-full bg-indigo-200"></span>
                                <span class="w-1.5 h-4 rounded-full bg-indigo-300"></span>
                                <span class="w-1.5 h-3 rounded-full bg-indigo-300"></span>
                                <span class="w-1.5 h-5 rounded-full bg-indigo-400"></span>
                                <span class="w-1.5 h-7 rounded-full bg-indigo-500"></span>
                            </div>

                        </div>
                    </div>

                    {{-- PEMAGANG AKTIF --}}
                    <div class="relative overflow-hidden bg-white rounded-xl border border-slate-100 shadow-sm">

                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 px-5 py-4">

                            <div class="flex items-center gap-3 sm:contents">
                                {{-- Icon --}}
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <circle cx="9" cy="7" r="4" stroke-width="1.8"/>
                                        <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M3 21v-2a6 6 0 0112 0v2"/>
                                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M16 11l2 2 4-5"/>
                                    </svg>
                                </div>

                                {{-- Judul --}}
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-800">Pemagang Aktif</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Status diterima</p>
                                </div>
                            </div>

                            {{-- Nilai --}}
                            <div class="hidden sm:block h-9 w-px bg-slate-100"></div>

                            <div class="min-w-0">
                                <div class="flex items-baseline gap-2">
                                    <h3 class="text-2xl font-bold text-slate-800 leading-none">{{ $pemagangAktif }}</h3>
                                    <span class="text-xs text-slate-400">orang</span>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Pendaftar yang telah diterima</p>
                            </div>

                            {{-- Indikator --}}
                            <div class="hidden sm:flex sm:ml-auto w-9 h-9 rounded-full bg-emerald-50 items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M5 12l4 4L19 6" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- GRAFIK --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

                    {{-- HEADER --}}
                    <div class="px-6 pt-5 pb-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                        <div class="flex items-center gap-3">

                            {{-- Icon --}}
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 20h17M8 17v-5m4 5V8m4 9V5m4 12V3"/>
                                </svg>
                            </div>

                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Perkembangan Pendaftar & Pemagang</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Perbandingan jumlah pendaftar dan pemagang aktif setiap tahun</p>
                            </div>

                        </div>

                        {{-- 5 TAHUN --}}
                        <div class="flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-200 text-sm text-slate-600">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div class="px-5 pb-5">

                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-5">

                            {{-- GRAFIK --}}
                            <div class="lg:col-span-3 rounded-xl bg-slate-50 border border-slate-100 p-4">
                                <div class="relative h-[300px]">
                                    <canvas id="statistikMagangChart"></canvas>
                                </div>
                            </div>

                            {{-- RINGKASAN --}}
                            <div class="rounded-xl bg-gradient-to-br from-indigo-50 to-white border border-indigo-100 p-5">

                                <h4 class="text-base font-bold text-indigo-600 mb-5">Ringkasan Tahun {{ $tahunSekarang }}</h4>

                                <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-3 h-3 rounded-full bg-indigo-400"></span>
                                        <span class="text-sm text-slate-600">Pendaftar</span>
                                    </div>
                                    <span class="font-bold text-slate-800">{{ $pendaftarTahunIni }}</span>
                                </div>

                                <div class="flex items-center justify-between py-4 border-b border-slate-200">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-3 h-3 rounded-full bg-pink-400"></span>
                                        <span class="text-sm text-slate-600">Diterima</span>
                                    </div>
                                    <span class="font-bold text-slate-800">{{ $diterimaTahunIni }}</span>
                                </div>

                                {{-- Persentase --}}
                                <div class="mt-5">
                                    <p class="text-sm font-semibold text-slate-700 mb-4">Tingkat Diterima</p>

                                    <div class="flex items-center gap-3">
                                        <div class="relative w-20 h-20 shrink-0">
                                            <svg class="w-20 h-20" viewBox="0 0 100 100">
                                                <circle cx="50" cy="50" r="40" fill="none" stroke="#e2e8f0" stroke-width="9"/>
                                                <circle
                                                    cx="50" cy="50" r="40" fill="none"
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
                                        <p class="text-xs leading-5 text-slate-500">Perbandingan pemagang diterima dari total pendaftar.</p>
                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- INFO --}}
                        <div class="mt-4 px-4 py-3 rounded-xl bg-blue-50 border border-blue-100 flex items-center gap-3">
                            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z"/>
                                </svg>
                            </div>
                            <p class="text-xs text-blue-700">Data diperbarui secara real-time dari sistem SiMagang.</p>
                        </div>

                    </div>

                </div>

            </div>
        </section>

        {{-- INSTANSI TUJUAN --}}
        <section class="py-8 sm:py-16 px-4 sm:px-6 bg-transparent">
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
                        />
                    @empty
                        <div class="col-span-full py-10 text-center">
                            <div class="text-4xl mb-3">🏢</div>
                            <h3 class="font-semibold text-slate-700">Belum ada instansi yang tersedia</h3>
                            <p class="text-sm text-slate-500 mt-1">Silakan cek kembali nanti.</p>
                        </div>
                    @endforelse

                </div>

            </div>
        </section>

       {{-- ==================== DOKUMENTASI & GALERI ==================== --}}
        <section id="dokumentasi" class="py-8 sm:py-16 px-4 sm:px-6 bg-transparent">
            <div class="max-w-6xl mx-auto">
                <x-section-heading eyebrow="Galeri & Kegiatan" title="Dokumentasi Aktivitas Magang">
                    Intip keseruan suasana kerja, kolaborasi proyek, dan bimbingan langsung bersama mentor profesional.
                </x-section-heading>

                {{--
                    BARU: setiap kartu sekarang bisa diklik -> membuka
                    lightbox (foto ukuran penuh + deskripsi lengkap,
                    tanpa perlu halaman baru). Kartu juga dibungkus
                    <button> (bukan <a>) supaya tetap bisa fokus/diakses
                    keyboard, dengan efek hover ring + shadow yang lebih
                    hidup dibanding sebelumnya (yang polos, tanpa umpan
                    balik saat disentuh).
                --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                    @forelse($dokumentasis as $index => $item)
                        <button type="button"
                                onclick="openGaleriLightbox({{ $index }})"
                                class="group text-left w-full rounded-2xl transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-100/60 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2">
                            <x-galeri-card
                                tag="{{ $item->judul_kegiatan }}"
                                judul="{{ $item->kategori_badge }}"
                                :gambar="asset('storage/' . $item->foto)"
                            >
                                {{ $item->deskripsi }}
                            </x-galeri-card>
                        </button>
                    @empty
                        <div class="col-span-full py-10 text-center">
                            <div class="text-4xl mb-3">📸</div>
                            <h3 class="font-semibold text-slate-700">Belum ada dokumentasi kegiatan</h3>
                            <p class="text-sm text-slate-500 mt-1">Dokumentasi kegiatan magang akan tampil di sini setelah diunggah admin.</p>
                        </div>
                    @endforelse
                </div>

                {{-- CTA — cukup sekali dipakai, jadi tidak perlu dikomponenkan --}}
                <div class="mt-12 sm:mt-16 bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 rounded-3xl p-6 sm:p-12 text-center text-white shadow-xl relative overflow-hidden">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(59,130,246,0.2),transparent_50%)] pointer-events-none"></div>
                    <h3 class="text-xl sm:text-3xl font-extrabold relative z-10">Tertarik Bergabung Menjadi Bagian dari Kami?</h3>
                    <p class="text-slate-300 text-xs sm:text-base mt-3 max-w-xl mx-auto relative z-10">
                        Segera siapkan berkas pengajuan surat pengantar dari kampusmu dan pilih instansi tujuanmu sekarang juga.
                    </p>
                    <div class="mt-6 sm:mt-8 relative z-10">
                        <a href="{{ route('user.pendaftaran.create') }}" class="inline-flex items-center gap-2 px-6 sm:px-8 py-3.5 sm:py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg transition duration-200">
                            <span>Mulai Ajukan Pendaftaran</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>

                {{-- Lightbox Dokumentasi --}}
                <div id="galeriLightbox" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeGaleriLightbox()"></div>

                    <div class="relative bg-white w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl max-h-[90vh] flex flex-col">
                        <button type="button" onclick="closeGaleriLightbox()"
                                class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-white/90 hover:bg-white text-slate-700 flex items-center justify-center shadow-md transition">
                            ✕
                        </button>

                        <div class="w-full h-64 sm:h-80 bg-slate-100 overflow-hidden shrink-0">
                            <img id="galeriLightboxImg" src="" alt="" class="w-full h-full object-cover">
                        </div>

                        <div class="p-6 overflow-y-auto">
                            <span id="galeriLightboxTag" class="inline-block px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold mb-3"></span>
                            <h3 id="galeriLightboxJudul" class="text-lg font-extrabold text-slate-900 mb-2"></h3>
                            <p id="galeriLightboxDeskripsi" class="text-sm text-slate-600 leading-relaxed whitespace-pre-line"></p>
                        </div>
                    </div>
                </div>

                @php
                    // FIX: sebelumnya @json(...) langsung berisi
                    // ->map(fn($item) => [...]) inline satu baris --
                    // itu memicu bug parser Blade ("Unclosed '[' ...")
                    // karena closure + array literal di dalam directive
                    // satu baris membuat penghitungan kurungnya salah.
                    // Sekarang datanya disusun dulu di sini sebagai
                    // variabel biasa, baru @json() menerima variabel
                    // polos -- pola ini tidak pernah bermasalah.
                    $galeriDataUntukJs = $dokumentasis->map(function ($item) {
                        return [
                            'tag' => $item->judul_kegiatan,
                            'judul' => $item->kategori_badge,
                            'gambar' => asset('storage/' . $item->foto),
                            'deskripsi' => $item->deskripsi,
                        ];
                    });
                @endphp
                
            </div>
        </section>

                {{-- CTA — cukup sekali dipakai, jadi tidak perlu dikomponenkan --}}
                <div class="mt-12 sm:mt-16 bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 rounded-3xl p-6 sm:p-12 text-center text-white shadow-xl relative overflow-hidden">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(59,130,246,0.2),transparent_50%)] pointer-events-none"></div>
                    <h3 class="text-xl sm:text-3xl font-extrabold relative z-10">Tertarik Bergabung Menjadi Bagian dari Kami?</h3>
                    <p class="text-slate-300 text-xs sm:text-base mt-3 max-w-xl mx-auto relative z-10">
                        Segera siapkan berkas pengajuan surat pengantar dari kampusmu dan pilih instansi tujuanmu sekarang juga.
                    </p>
                    <div class="mt-6 sm:mt-8 relative z-10">
                        <a href="{{ route('user.pendaftaran.create') }}" class="inline-flex items-center gap-2 px-6 sm:px-8 py-3.5 sm:py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg transition duration-200">
                            <span>Mulai Ajukan Pendaftaran</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>

                {{-- Lightbox Dokumentasi --}}
                <div id="galeriLightbox" class="hidden fixed inset-0 z-[80] flex items-center justify-center p-4">
                    ...
                </div>

                @php
                    $galeriDataUntukJs = $dokumentasis->map(function ($item) {
                        return [
                            'tag' => $item->judul_kegiatan,
                            'judul' => $item->kategori_badge,
                            'gambar' => asset('storage/' . $item->foto),
                            'deskripsi' => $item->deskripsi,
                        ];
                    });
                @endphp

            </div>
        </section>

    </x-flowing-bg>

    <x-footer />

    <x-footer />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const statistik = @json($statistikMagang);
    const tahun = statistik.map(item => item.tahun);
    const pendaftar = statistik.map(item => Number(item.pendaftar));
    const diterima = statistik.map(item => Number(item.diterima));
    const ctx = document.getElementById('statistikMagangChart');

    new Chart(ctx, {
        type: 'bar',

        data: {
            labels: tahun,

            datasets: [
                {
                    label: 'Pendaftar',
                    data: pendaftar,

                    backgroundColor: '#6385F5',

                    borderRadius: 7,
                    borderSkipped: false,

                    barPercentage: 0.65,
                    categoryPercentage: 0.65
                },

                {
                    label: 'Diterima',
                    data: diterima,

                    backgroundColor: '#F45B91',

                    borderRadius: 7,
                    borderSkipped: false,

                    barPercentage: 0.65,
                    categoryPercentage: 0.65
                }
            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            interaction: {
                mode: 'index',
                intersect: false
            },

            plugins: {

                legend: {
                    position: 'top',

                    labels: {
                        usePointStyle: true,
                        pointStyle: 'rectRounded',

                        padding: 25,

                        color: '#475569',

                        font: {
                            size: 13
                        }
                    }
                },

                tooltip: {
                    backgroundColor: '#172554',

                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',

                    padding: 12,

                    cornerRadius: 10,

                    displayColors: true
                }
            },

            scales: {

                x: {
                    grid: {
                        display: false
                    },

                    ticks: {
                        color: '#475569',

                        font: {
                            size: 13
                        }
                    }
                },

                y: {
                    beginAtZero: true,

                    ticks: {
                        precision: 0,

                        color: '#64748b',

                        font: {
                            size: 12
                        }
                    },

                    grid: {
                        color: '#e2e8f0',

                        borderDash: [5, 5]
                    }
                }
            }
        }
    });
  
                    const galeriData = @json($galeriDataUntukJs);

                    function openGaleriLightbox(index) {
                        const data = galeriData[index];
                        if (!data) return;

                        document.getElementById('galeriLightboxImg').src = data.gambar;
                        document.getElementById('galeriLightboxImg').alt = data.judul;
                        document.getElementById('galeriLightboxTag').textContent = data.tag;
                        document.getElementById('galeriLightboxJudul').textContent = data.judul;
                        document.getElementById('galeriLightboxDeskripsi').textContent = data.deskripsi;

                        document.getElementById('galeriLightbox').classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                    }

                    function closeGaleriLightbox() {
                        document.getElementById('galeriLightbox').classList.add('hidden');
                        document.body.style.overflow = '';
                    }

                    document.addEventListener('keydown', function (e) {
                        if (e.key === 'Escape') closeGaleriLightbox();
                    });
</script>
</body>
</html>