@extends('user.layout.app')

@section('title', 'Dashboard User')

@section('content')

{{-- =====================================================================
     DATA TAMPILAN (semua perhitungan dikumpulkan di sini)
     ===================================================================== --}}
@php
    $p            = $pendaftaran;
    $profilKurang = $user->profilKurang();

    /* ---------- Status pendaftaran ---------- */
    $status = strtolower($p->status ?? 'draft');

    $statusConfig = [
        'draft' => [
            'label' => 'Draft',
            'description' => 'Pendaftaran belum dikirim dan masih dapat dilanjutkan.',
            'dot' => 'bg-slate-500',
            'iconBg' => 'bg-slate-100 dark:bg-slate-800',
            'iconColor' => 'text-slate-600 dark:text-slate-300',
            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z',
        ],
        'pending' => [
            'label' => 'Menunggu Verifikasi',
            'description' => 'Pengajuan sedang diperiksa oleh admin.',
            'dot' => 'bg-amber-500',
            'iconBg' => 'bg-amber-50 dark:bg-amber-500/10',
            'iconColor' => 'text-amber-600 dark:text-amber-400',
            'icon' => 'M12 6v6l4 2',
        ],
        'revisi' => [
            'label' => 'Perlu Revisi',
            'description' => 'Pengajuan perlu diperbaiki sebelum dapat diproses kembali.',
            'dot' => 'bg-orange-500',
            'iconBg' => 'bg-orange-50 dark:bg-orange-500/10',
            'iconColor' => 'text-orange-600 dark:text-orange-400',
            'icon' => 'M16.862 3.487a2.25 2.25 0 113.182 3.182L8.25 18.462 4 19.5l1.038-4.25L16.862 3.487z',
        ],
        'diterima' => [
            'label' => 'Diterima',
            'description' => 'Selamat! Pengajuan magang kamu telah diterima.',
            'dot' => 'bg-emerald-500',
            'iconBg' => 'bg-emerald-50 dark:bg-emerald-500/10',
            'iconColor' => 'text-emerald-600 dark:text-emerald-400',
            'icon' => 'M5 13l4 4L19 7',
        ],
        'ditolak' => [
            'label' => 'Ditolak',
            'description' => 'Pengajuan sebelumnya tidak dapat dilanjutkan.',
            'dot' => 'bg-rose-500',
            'iconBg' => 'bg-rose-50 dark:bg-rose-500/10',
            'iconColor' => 'text-rose-600 dark:text-rose-400',
            'icon' => 'M6 18L18 6M6 6l12 12',
        ],
        'selesai' => [
            'label' => 'Selesai',
            'description' => 'Masa magang pada pengajuan ini telah selesai.',
            'dot' => 'bg-cyan-500',
            'iconBg' => 'bg-cyan-50 dark:bg-cyan-500/10',
            'iconColor' => 'text-cyan-700 dark:text-cyan-400',
            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z',
        ],
    ];

    $config = $statusConfig[$status] ?? $statusConfig['draft'];

    /* ---------- Aksi utama: [judul, keterangan, url, warna] ---------- */
    $aksi = match (true) {
        ! $p => ['Daftar Magang', 'Buat pengajuan baru', route('user.pendaftaran.create'), 'blue'],

        $p->status === 'draft' => ['Lanjutkan Draft', 'Lengkapi dan kirim pengajuan', route('user.pendaftaran.edit', $p->id), 'amber'],

        $p->status === 'revisi' => ['Perbaiki Pengajuan', 'Lengkapi revisi dari admin', route('user.pendaftaran.edit', $p->id), 'orange'],

        $p->statusSertifikat() === 'diterbitkan' => [
            'Unduh Sertifikat', 'Sertifikat telah diterbitkan',
            route('user.pendaftaran.sertifikat', ['pendaftaran' => $p->id, 'unduh' => 1]), 'emerald',
        ],

        $p->statusSertifikat() === 'diproses' => [
            'Sertifikat sedang diproses', 'Tombol unduh muncul setelah diterbitkan',
            route('user.pendaftaran.status'), 'amber',
        ],

        in_array($p->status, ['ditolak', 'selesai'], true) => ['Daftar Magang Lagi', 'Buat pengajuan baru', route('user.pendaftaran.create'), 'blue'],

        $p->statusSurat() === 'diterbitkan' => [
            'Unduh Surat Balasan', 'Surat balasan telah diterbitkan',
            route('user.pendaftaran.surat', ['pendaftaran' => $p->id, 'unduh' => 1]), 'emerald',
        ],

        $p->statusSurat() === 'diproses' => [
            'Surat balasan sedang diproses', 'Tombol unduh muncul setelah diterbitkan',
            route('user.pendaftaran.status'), 'amber',
        ],

        default => ['Cek Pengajuan', 'Lihat perkembangan pengajuan', route('user.pendaftaran.status'), 'blue'],
    };

    [$garis, $tombol] = [
        'blue'    => ['hover:border-blue-300 dark:hover:border-blue-500/50',       'bg-blue-600'],
        'amber'   => ['hover:border-amber-300 dark:hover:border-amber-500/50',     'bg-amber-500'],
        'orange'  => ['hover:border-orange-300 dark:hover:border-orange-500/50',   'bg-orange-500'],
        'emerald' => ['hover:border-emerald-300 dark:hover:border-emerald-500/50', 'bg-emerald-600'],
    ][$aksi[3]];

    /* ---------- Instansi: tampil 6 saja, sisanya lewat halaman "lihat semua" ---------- */
    $batasDinas  = 6;
    $dinasTampil = $dinases->take($batasDinas);
    $totalDinas  = $dinases->count();

    /* ---------- Menu cepat ---------- */
    $tema = [
        'blue' => [
            'chip'  => 'bg-blue-50 text-blue-600 ring-blue-100 dark:bg-blue-500/15 dark:text-blue-300 dark:ring-blue-400/20',
            'glow'  => 'bg-blue-300/30 dark:bg-blue-500/20',
            'hover' => 'hover:border-blue-300 hover:shadow-blue-100/70 dark:hover:border-blue-500/50',
            'link'  => 'text-blue-700 dark:text-blue-300',
            'badge' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-300',
        ],
        'violet' => [
            'chip'  => 'bg-violet-50 text-violet-600 ring-violet-100 dark:bg-violet-500/15 dark:text-violet-300 dark:ring-violet-400/20',
            'glow'  => 'bg-violet-300/30 dark:bg-violet-500/20',
            'hover' => 'hover:border-violet-300 hover:shadow-violet-100/70 dark:hover:border-violet-500/50',
            'link'  => 'text-violet-700 dark:text-violet-300',
            'badge' => 'bg-violet-50 text-violet-700 dark:bg-violet-500/15 dark:text-violet-300',
        ],
        'cyan' => [
            'chip'  => 'bg-cyan-50 text-cyan-600 ring-cyan-100 dark:bg-cyan-500/15 dark:text-cyan-300 dark:ring-cyan-400/20',
            'glow'  => 'bg-cyan-300/30 dark:bg-cyan-500/20',
            'hover' => 'hover:border-cyan-300 hover:shadow-cyan-100/70 dark:hover:border-cyan-500/50',
            'link'  => 'text-cyan-700 dark:text-cyan-300',
            'badge' => 'bg-cyan-50 text-cyan-700 dark:bg-cyan-500/15 dark:text-cyan-300',
        ],
        'amber' => [
            'chip'  => 'bg-amber-50 text-amber-600 ring-amber-100 dark:bg-amber-500/15 dark:text-amber-300 dark:ring-amber-400/20',
            'glow'  => 'bg-amber-300/30 dark:bg-amber-500/20',
            'hover' => 'hover:border-amber-300 hover:shadow-amber-100/70 dark:hover:border-amber-500/50',
            'link'  => 'text-amber-700 dark:text-amber-300',
            'badge' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300',
        ],
    ];

    // [judul, keterangan, url, path ikon, tema, label kecil (opsional)]
    $menu = [
        ['Daftar Magang', 'Ajukan pendaftaran baru ke instansi pilihan kamu.',
            route('user.pendaftaran.create'), 'M12 4v16m8-8H4', 'blue', null],

        ['Status Pengajuan', 'Pantau proses dan perkembangan pendaftaran.',
            route('user.pendaftaran.status'),
            'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z', 'violet',
            $p ? $config['label'] : 'Belum ada'],

        ['Informasi Magang', 'Baca panduan, syarat, dan ketentuan magang.',
            route('informasi'),
            'M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z', 'cyan', null],

        ['Instansi Tersedia', 'Lihat pilihan instansi yang membuka magang.',
            '#instansi', 'M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6', 'amber', $totalDinas . ' instansi'],
    ];
@endphp

<div class="px-4 sm:px-6">
<div class="mx-auto max-w-6xl space-y-14 sm:space-y-20">

    {{-- =================== ATAS: SAMBUTAN, PERINGATAN, STATUS =================== --}}
    <div class="space-y-4 sm:space-y-5">

        {{-- ---------- Hero ---------- --}}
        <section class="relative overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-white via-blue-50 to-cyan-50
                        p-5 shadow-sm transition-all duration-300 sm:p-10
                        dark:border-blue-900/60 dark:from-[#101b35] dark:via-[#14264a] dark:to-[#0b172d] dark:shadow-none">

            {{-- Cahaya samar --}}
            <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-blue-400/15 blur-3xl dark:bg-blue-500/10"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-16 h-56 w-56 rounded-full bg-cyan-300/10 blur-3xl dark:bg-cyan-500/10"></div>

            <div class="relative z-10 flex flex-col gap-5 sm:gap-6 lg:flex-row lg:items-center lg:justify-between">

                <div class="max-w-2xl">
                    <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-blue-100 bg-white/80 px-3 py-1 sm:mb-4
                                dark:border-blue-400/20 dark:bg-blue-400/10">
                        <span class="h-2 w-2 rounded-full bg-blue-500 dark:bg-cyan-400"></span>
                        <span class="text-xs font-bold uppercase tracking-wide text-blue-700 dark:text-cyan-300">Dashboard Pengguna</span>
                    </div>

                    <h1 class="text-2xl font-extrabold leading-tight tracking-tight text-slate-800 sm:text-3xl lg:text-4xl dark:text-white">
                        Selamat datang,
                        <span class="bg-gradient-to-r from-blue-700 via-cyan-600 to-sky-500 bg-clip-text text-transparent
                                     dark:from-blue-400 dark:via-cyan-300 dark:to-cyan-200">{{ $user->name }}</span>
                    </h1>

                    <p class="mt-2.5 max-w-xl text-sm leading-relaxed text-slate-600 sm:mt-3 sm:text-base dark:text-slate-300">
                        Kelola pendaftaran PKL / magang, pantau status pengajuan, dan temukan instansi tujuan yang tersedia dengan mudah.
                    </p>
                </div>

                {{-- Tombol profil --}}
                <a href="{{ route('user.profil') }}"
                   class="group flex shrink-0 items-center justify-between gap-4 rounded-2xl border border-blue-200 bg-white p-4 shadow-sm
                          transition-all hover:border-blue-300 hover:bg-blue-50 hover:shadow-md
                          dark:border-slate-700 dark:bg-[#1c2b49] dark:shadow-none dark:hover:border-cyan-500/40 dark:hover:bg-[#263a5e]">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition-colors
                                    group-hover:bg-blue-600 group-hover:text-white
                                    dark:bg-blue-500/15 dark:text-cyan-300 dark:group-hover:bg-cyan-500 dark:group-hover:text-white">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Pengaturan Akun</p>
                            <p class="text-sm font-bold text-slate-800 dark:text-white">Profil Saya</p>
                        </div>
                    </div>

                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition-colors
                                group-hover:bg-blue-100 group-hover:text-blue-600
                                dark:bg-slate-700 dark:text-slate-300 dark:group-hover:bg-cyan-500/20 dark:group-hover:text-cyan-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
            </div>
        </section>

        {{-- ---------- Peringatan lengkapi profil ---------- --}}
        @if ($profilKurang)
            <section class="rounded-2xl border border-amber-200 bg-amber-50 p-4 transition-colors duration-300 sm:rounded-3xl sm:p-5
                            dark:border-amber-500/25 dark:bg-amber-500/[0.07]">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4">

                    <div class="flex min-w-0 items-start gap-3 sm:items-center">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-600
                                    dark:bg-amber-500/15 dark:text-amber-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-bold text-amber-800 dark:text-amber-300">Lengkapi profil kamu terlebih dahulu</p>
                            <p class="mt-0.5 text-sm leading-relaxed text-amber-700 dark:text-amber-200/80">
                                Yang belum terisi: <strong>{{ implode(', ', $profilKurang) }}</strong>.
                                Lengkapi profil supaya data ini otomatis mengisi formulir pendaftaran magang nanti.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('user.profil') }}"
                       class="inline-flex w-full shrink-0 items-center justify-center gap-2 rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-bold
                              text-white transition-colors hover:bg-amber-700 sm:w-auto
                              dark:bg-amber-500 dark:text-slate-950 dark:hover:bg-amber-400">
                        Lengkapi Sekarang
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </section>
        @endif

        {{-- ---------- Status pendaftaran + aksi utama ---------- --}}
        <section class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-3">

            {{-- Status pendaftaran --}}
            <div class="flex flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition
                        hover:shadow-md lg:col-span-2 dark:border-slate-800 dark:bg-slate-900 dark:shadow-none dark:hover:border-slate-700">
                <div class="flex h-full flex-col justify-between p-5 sm:p-7">

                    @if (! $p)
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="mb-2 flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Pendaftaran</p>
                                </div>
                                <h2 class="text-xl font-extrabold text-slate-800 sm:text-2xl dark:text-slate-100">Belum Ada Pendaftaran</h2>
                                <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">Anda belum mengajukan pendaftaran magang.</p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-slate-100 bg-slate-50 text-slate-500
                                        dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-400">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2"/>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-6 border-t border-slate-100 pt-5 dark:border-slate-800">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Belum memiliki pengajuan?</p>
                            <p class="mt-0.5 text-sm font-medium text-slate-700 dark:text-slate-200">Mulai pendaftaran dan lengkapi data magang kamu.</p>
                        </div>

                    @else
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="mb-2 flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full {{ $config['dot'] }}"></span>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Status Pendaftaran</p>
                                </div>
                                <h2 class="text-xl font-extrabold text-slate-800 sm:text-2xl dark:text-slate-100">{{ $config['label'] }}</h2>
                                <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                                    {{ $p->dinas_tujuan ?? 'Instansi belum dipilih' }}
                                </p>
                            </div>

                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl {{ $config['iconBg'] }} {{ $config['iconColor'] }}">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"/>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-6 border-t border-slate-100 pt-5 dark:border-slate-800">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Periode Magang</p>
                                    <p class="mt-1 text-sm font-bold text-slate-700 dark:text-slate-200">
                                        @if ($p->tanggal_mulai && $p->tanggal_selesai)
                                            {{ \Carbon\Carbon::parse($p->tanggal_mulai)->translatedFormat('d M Y') }}
                                            <span class="mx-1 text-slate-300 dark:text-slate-600">—</span>
                                            {{ \Carbon\Carbon::parse($p->tanggal_selesai)->translatedFormat('d M Y') }}
                                        @else
                                            Belum ditentukan
                                        @endif
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $config['description'] }}</p>
                                </div>

                                <a href="{{ route('user.pendaftaran.status') }}"
                                   class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5
                                          text-sm font-bold text-slate-700 transition
                                          hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700
                                          dark:border-slate-800 dark:bg-slate-950/60 dark:text-slate-200
                                          dark:hover:border-blue-500/30 dark:hover:bg-blue-500/10 dark:hover:text-blue-300">
                                    Lihat Status
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Aksi utama --}}
            <div class="relative isolate flex flex-col overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-50 via-white to-cyan-50
                        p-5 shadow-sm transition-colors duration-300 sm:p-7
                        dark:border-slate-700 dark:bg-slate-800 dark:bg-none dark:shadow-none">

                <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-blue-200/30 blur-2xl dark:bg-cyan-500/10"></div>

                <div class="relative z-10 flex h-full flex-col">
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-blue-500 dark:bg-cyan-400"></span>
                        <p class="text-xs font-bold uppercase tracking-wider text-blue-700 dark:text-cyan-300">Aksi Utama</p>
                    </div>

                    <h2 class="mt-2 text-xl font-extrabold text-slate-800 dark:text-slate-100">Mulai dari sini</h2>
                    <p class="mb-5 mt-1 text-sm leading-relaxed text-slate-600 dark:text-slate-300">Akses fitur utama pendaftaran dengan cepat.</p>

                    <a href="{{ $aksi[2] }}"
                       class="group mt-auto flex w-full items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3.5
                              transition hover:shadow-md dark:border-slate-700 dark:bg-slate-800 dark:hover:shadow-none {{ $garis }}">
                        <div>
                            <p class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $aksi[0] }}</p>
                            <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ $aksi[1] }}</p>
                        </div>
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $tombol }} text-white transition group-hover:translate-x-0.5">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>

        </section>
    </div>

    {{-- =================== MENU CEPAT =================== --}}
    <section>
        <div class="mb-4 sm:mb-5">
            <p class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Navigasi</p>
            <h2 class="mt-1 text-lg font-extrabold text-slate-800 sm:text-2xl dark:text-slate-100">Menu Cepat</h2>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
            @foreach ($menu as [$judul, $teks, $href, $ikon, $warna, $label])
                @php $c = $tema[$warna]; @endphp

                <a href="{{ $href }}"
                   class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm
                          transition duration-200 hover:-translate-y-0.5 hover:shadow-md
                          focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500
                          dark:border-slate-700 dark:bg-slate-900 dark:shadow-none dark:hover:shadow-none {{ $c['hover'] }}">

                    {{-- Cahaya warna di pojok --}}
                    <div class="pointer-events-none absolute -right-8 -top-8 h-24 w-24 rounded-full opacity-70 blur-2xl transition
                                group-hover:opacity-100 {{ $c['glow'] }}"></div>

                    <div class="relative flex items-start justify-between gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl ring-1 ring-inset {{ $c['chip'] }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $ikon }}"/>
                            </svg>
                        </div>

                        @if ($label)
                            <span class="max-w-[65%] truncate rounded-full px-2 py-0.5 text-xs font-bold {{ $c['badge'] }}">{{ $label }}</span>
                        @endif
                    </div>

                    <h3 class="relative mt-3 flex items-center justify-between gap-2 text-sm font-bold text-slate-900 sm:text-base dark:text-white">
                        <span class="truncate">{{ $judul }}</span>
                        <svg class="h-4 w-4 shrink-0 text-slate-400 transition duration-200 group-hover:translate-x-1 group-hover:text-slate-600
                                    dark:text-slate-500 dark:group-hover:text-slate-300"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </h3>

                    <p class="relative mt-1 line-clamp-2 text-xs leading-relaxed text-slate-600 sm:text-sm dark:text-slate-300">{{ $teks }}</p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- =================== INSTANSI TERSEDIA (sama seperti beranda) =================== --}}
    <section id="instansi" class="scroll-mt-24">
        <x-section-heading eyebrow="Instansi Tujuan" title="Instansi yang Tersedia">
            Pilihan instansi yang saat ini membuka pendaftaran melalui sistem. Total {{ $totalDinas }} instansi aktif.
        </x-section-heading>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3">
            @forelse ($dinasTampil as $dinas)
                <x-instansi-card
                    :slug="Str::slug($dinas->nama_dinas)"
                    :nama="$dinas->nama_dinas"
                    :deskripsi="$dinas->deskripsi"
                    :gambar="$dinas->gambar ?? null"
                    :rekap="$dinas->rekap ?? null"
                />
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center dark:border-slate-700 dark:bg-slate-900">
                    <x-ikon name="building" class="mx-auto mb-3 h-10 w-10 text-slate-400" />
                    <h3 class="font-semibold text-slate-700 dark:text-slate-200">Belum ada instansi tersedia</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Silakan cek kembali nanti.</p>
                </div>
            @endforelse
        </div>

        @if ($totalDinas > $batasDinas)
            <div class="mt-8 flex flex-col items-center gap-2 sm:mt-10">
                <x-button :href="route('instansi.index')" variant="secondary" size="sm">
                    Lihat semua dinas ({{ $totalDinas }})
                </x-button>
                <p class="text-sm text-slate-500 dark:text-slate-400">Menampilkan {{ $dinasTampil->count() }} dari {{ $totalDinas }} dinas</p>
            </div>
        @endif
    </section>

</div>
</div>

{{-- =================== POPUP PENDAFTARAN DITERIMA =================== --}}
@if ($pendaftaran?->status === 'diterima')
    @include('user.partials.popup-diterima')
@endif

@endsection