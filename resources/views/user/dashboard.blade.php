@extends('user.layout.app')

@section('title', 'Dashboard User')

@section('content')

</div>
{{-- Hero Dashboard --}}
<section class="px-4 sm:px-6">
    <div class="max-w-6xl mx-auto">
        
<div class="relative overflow-hidden rounded-3xl
            border border-blue-100 dark:border-blue-900/60
            bg-gradient-to-br from-white via-blue-50 to-cyan-50
            dark:from-[#101b35] dark:via-[#14264a] dark:to-[#0b172d]
            p-5 sm:p-10
            shadow-sm dark:shadow-none
            transition-all duration-300">


            {{-- Cahaya samar --}}
            <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full
                        bg-blue-400/15 dark:bg-blue-500/10
                        blur-3xl pointer-events-none"></div>

            <div class="absolute -left-16 -bottom-20 w-56 h-56 rounded-full
                        bg-cyan-300/10 dark:bg-cyan-500/10
                        blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 sm:gap-6">

                {{-- Informasi Selamat Datang --}}
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full
                                bg-white/80 dark:bg-blue-400/10
                                border border-blue-100 dark:border-blue-400/20
                                mb-3 sm:mb-4">

                        <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400"></span>

                        <span class="text-[11px] sm:text-xs font-bold
                                     text-blue-700 dark:text-cyan-300
                                     tracking-wide uppercase">
                            Dashboard Pengguna
                        </span>
                    </div>

                    <h1 class="text-xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight
                               text-slate-800 dark:text-white leading-tight">
                        Selamat datang,
                        <span class="bg-gradient-to-r
                                     from-blue-700 via-cyan-600 to-sky-500
                                     dark:from-blue-400 dark:via-cyan-300 dark:to-cyan-200
                                     bg-clip-text text-transparent">
                            {{ $user->name }}
                        </span>
                    </h1>

                    <p class="mt-2.5 sm:mt-3 text-sm sm:text-base
                              text-slate-600 dark:text-slate-300
                              leading-relaxed max-w-xl">
                        Kelola pendaftaran PKL / magang, pantau status pengajuan,
                        dan temukan instansi tujuan yang tersedia dengan mudah.
                    </p>
                </div>

                {{-- Tombol Profil --}}
                <div class="shrink-0">
                    <a href="{{ route('user.profil') }}"
                       class="group flex items-center justify-between gap-4
                              bg-white dark:bg-[#1c2b49]
                              hover:bg-blue-50 dark:hover:bg-[#263a5e]
                              border border-blue-200 dark:border-slate-700
                              rounded-2xl p-4 shadow-sm hover:shadow-md
                              dark:shadow-none dark:hover:shadow-none
                              hover:border-blue-300 dark:hover:border-cyan-500/40
                              transition-all">

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl
                                        bg-blue-50 dark:bg-blue-500/15
                                        text-blue-600 dark:text-cyan-300
                                        flex items-center justify-center shrink-0
                                        group-hover:bg-blue-600 group-hover:text-white
                                        dark:group-hover:bg-cyan-500 dark:group-hover:text-white
                                        transition-colors">

                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>

                            <div class="text-left">
                                <p class="text-[11px] font-bold
                                          text-slate-500 dark:text-slate-400
                                          uppercase tracking-wider">
                                    Pengaturan Akun
                                </p>

                                <p class="text-sm font-bold
                                          text-slate-800 dark:text-white">
                                    Profil Saya
                                </p>
                            </div>
                        </div>

                        <div class="w-8 h-8 shrink-0 rounded-full
                                    bg-slate-100 dark:bg-slate-700
                                    flex items-center justify-center
                                    text-slate-500 dark:text-slate-300
                                    group-hover:bg-blue-100 dark:group-hover:bg-cyan-500/20
                                    group-hover:text-blue-600 dark:group-hover:text-cyan-300
                                    transition-colors">

                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- Peringatan Lengkapi Profil --}}
@php
    $profilKurang = $user->profilKurang();
@endphp
@if ($profilKurang)
    <section class="px-4 sm:px-6 mt-4 sm:mt-5">
        <div class="max-w-6xl mx-auto">
            <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl
                        border border-amber-200 dark:border-amber-500/25
                        bg-amber-50 dark:bg-amber-500/[0.07]
                        p-4 sm:p-5 transition-colors duration-300">

                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">

                    <div class="flex items-start sm:items-center gap-3 min-w-0">
                        <div class="w-10 h-10 shrink-0 rounded-xl
                                    bg-amber-100 dark:bg-amber-500/15
                                    text-amber-600 dark:text-amber-400
                                    flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <p class="text-sm font-bold text-amber-800 dark:text-amber-300">
                                Lengkapi profil kamu terlebih dahulu
                            </p>
                            <p class="text-xs text-amber-700 dark:text-amber-200/80 mt-0.5 leading-relaxed">
                                Yang belum terisi: <strong>{{ implode(', ', $profilKurang) }}</strong>. Lengkapi profil supaya data ini otomatis mengisi formulir pendaftaran magang nanti.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('user.profil') }}"
                       class="shrink-0 w-full sm:w-auto inline-flex items-center justify-center gap-2
                              px-4 py-2.5
                              bg-amber-600 hover:bg-amber-700
                              dark:bg-amber-500 dark:hover:bg-amber-400
                              text-white dark:text-slate-950
                              text-xs font-bold rounded-xl transition-colors">
                        Lengkapi Sekarang
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>

                </div>
            </div>
        </div>
    </section>
@endif

{{-- Status + Aksi --}}
<section class="px-4 sm:px-6 mt-5 sm:mt-6">
    <div class="max-w-6xl mx-auto">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

            {{-- Status Pendaftaran --}}
            <div class="lg:col-span-2
                        bg-white dark:bg-slate-900
                        rounded-3xl
                        border border-slate-200 dark:border-slate-800
                        shadow-sm hover:shadow-md dark:shadow-none dark:hover:border-slate-700 transition
                        overflow-hidden flex flex-col justify-between">

                @if (!$pendaftaran)
                    <div class="p-5 sm:p-7">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Status Pendaftaran
                                    </p>
                                </div>
                                <h2 class="text-lg sm:text-2xl font-extrabold text-slate-800 dark:text-slate-100">
                                    Belum Ada Pendaftaran
                                </h2>
                                <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                                    Anda belum mengajukan pendaftaran magang.
                                </p>
                            </div>
                            <div class="w-11 h-11 sm:w-12 sm:h-12 shrink-0 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2"/>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-6 pt-4 sm:pt-5 border-t border-slate-100 dark:border-slate-800">
                            <p class="text-xs text-slate-400 dark:text-slate-500">Belum memiliki pengajuan?</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300 mt-0.5">Mulai pendaftaran dan lengkapi data magang kamu.</p>
                        </div>
                    </div>

                @else
                    @php
                        $status = strtolower($pendaftaran->status ?? 'draft');

                        $statusConfig = [
                            'draft' => [
                                'label' => 'Draft',
                                'description' => 'Pendaftaran belum dikirim dan masih dapat dilanjutkan.',
                                'class' => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-700',
                                'dot' => 'bg-slate-500',
                                'iconBg' => 'bg-slate-100 dark:bg-slate-800',
                                'iconColor' => 'text-slate-600 dark:text-slate-300',
                            ],
                            'pending' => [
                                'label' => 'Menunggu Verifikasi',
                                'description' => 'Pengajuan sedang diperiksa oleh admin.',
                                'class' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-500/25',
                                'dot' => 'bg-amber-500',
                                'iconBg' => 'bg-amber-50 dark:bg-amber-500/10',
                                'iconColor' => 'text-amber-600 dark:text-amber-400',
                            ],
                            'revisi' => [
                                'label' => 'Perlu Revisi',
                                'description' => 'Pengajuan perlu diperbaiki sebelum dapat diproses kembali.',
                                'class' => 'bg-orange-50 dark:bg-orange-500/10 text-orange-700 dark:text-orange-300 border-orange-200 dark:border-orange-500/25',
                                'dot' => 'bg-orange-500',
                                'iconBg' => 'bg-orange-50 dark:bg-orange-500/10',
                                'iconColor' => 'text-orange-600 dark:text-orange-400',
                            ],
                            'diterima' => [
                                'label' => 'Diterima',
                                'description' => 'Selamat! Pengajuan magang kamu telah diterima.',
                                'class' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-500/25',
                                'dot' => 'bg-emerald-500',
                                'iconBg' => 'bg-emerald-50 dark:bg-emerald-500/10',
                                'iconColor' => 'text-emerald-600 dark:text-emerald-400',
                            ],
                            'ditolak' => [
                                'label' => 'Ditolak',
                                'description' => 'Pengajuan sebelumnya tidak dapat dilanjutkan.',
                                'class' => 'bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-500/25',
                                'dot' => 'bg-rose-500',
                                'iconBg' => 'bg-rose-50 dark:bg-rose-500/10',
                                'iconColor' => 'text-rose-600 dark:text-rose-400',
                            ],
                            'selesai' => [
                            'label' => 'Selesai',
                            'description' => 'Masa magang pada pengajuan ini telah selesai.',
                            'class' => 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-800 dark:text-cyan-300 border-cyan-200 dark:border-cyan-500/25',
                            'dot' => 'bg-cyan-500',
                            'iconBg' => 'bg-cyan-50 dark:bg-cyan-500/10',
                            'iconColor' => 'text-cyan-700 dark:text-cyan-400',
                        ],
                        ];

                        $config = $statusConfig[$status] ?? $statusConfig['draft'];
                    @endphp

                    <div class="p-5 sm:p-7 flex flex-col justify-between h-full">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                        Status Pendaftaran
                                    </p>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border text-[10px] font-bold {{ $config['class'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
                                        {{ $config['label'] }}
                                    </span>
                                </div>

                                <h2 class="text-lg sm:text-2xl font-extrabold text-slate-800 dark:text-slate-100">
                                    {{ $config['label'] }}
                                </h2>

                                <p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">
                                    {{ $pendaftaran->dinas_tujuan ?? 'Instansi belum dipilih' }}
                                </p>
                            </div>

                            <div class="w-11 h-11 sm:w-12 sm:h-12 shrink-0 rounded-2xl {{ $config['iconBg'] }} {{ $config['iconColor'] }} flex items-center justify-center">
                                @if ($status === 'diterima')
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @elseif ($status === 'ditolak')
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                @elseif ($status === 'pending')
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                                    </svg>
                                @elseif ($status === 'revisi')
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L8.25 18.462 4 19.5l1.038-4.25L16.862 3.487z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"/>
                                    </svg>
                                @endif
                            </div>
                        </div>

                        <div class="mt-5 sm:mt-6 pt-4 sm:pt-5 border-t border-slate-100 dark:border-slate-800">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                                <div>
                                    <p class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wide">
                                        Periode Magang
                                    </p>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200 mt-1">
                                        @if ($pendaftaran->tanggal_mulai && $pendaftaran->tanggal_selesai)
                                            {{ \Carbon\Carbon::parse($pendaftaran->tanggal_mulai)->translatedFormat('d M Y') }}
                                            <span class="text-slate-300 dark:text-slate-600 mx-1">—</span>
                                            {{ \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->translatedFormat('d M Y') }}
                                        @else
                                            Belum ditentukan
                                        @endif
                                    </p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">
                                        {{ $config['description'] }}
                                    </p>
                                </div>

                                <a href="{{ route('user.pendaftaran.status') }}"
                                   class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl
                                          bg-slate-50 dark:bg-slate-950/60
                                          border border-slate-200 dark:border-slate-800
                                          hover:bg-blue-50 dark:hover:bg-blue-500/10
                                          hover:border-blue-200 dark:hover:border-blue-500/30
                                          hover:text-blue-700 dark:hover:text-blue-300
                                          text-slate-700 dark:text-slate-200 text-xs font-bold transition">
                                    Lihat Status
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            

{{-- Aksi Utama --}}
<div class="relative isolate overflow-hidden rounded-3xl
            border border-blue-100
            bg-gradient-to-br from-blue-50 via-white to-cyan-50
            p-5 sm:p-7 shadow-sm
            dark:border-slate-700 dark:bg-none dark:bg-slate-800 dark:shadow-none
            flex flex-col justify-between
            transition-colors duration-300">

    {{-- Dekorasi cahaya --}}
    <div class="absolute -right-10 -top-10 w-32 h-32
                rounded-full bg-blue-200/30 dark:bg-cyan-500/10
                blur-2xl pointer-events-none"></div>

    {{-- Konten --}}
    <div class="relative z-10">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-500 dark:bg-cyan-400"></span>
            <p class="text-[11px] font-bold uppercase tracking-wider
                      text-blue-700 dark:text-cyan-300">
                Aksi Utama
            </p>
        </div>

        <h2 class="mt-2 text-lg sm:text-xl font-extrabold
                   text-slate-800 dark:text-slate-100">
            Mulai dari sini
        </h2>

        <p class="mt-1 text-sm leading-relaxed
                  text-slate-500 dark:text-slate-300">
            Akses fitur utama pendaftaran dengan cepat.
        </p>


                    
@php
    $p = $pendaftaran;

    $aksi = match (true) {
        ! $p => [
            'Daftar Magang',
            'Buat pengajuan baru',
            route('user.pendaftaran.create'),
            'blue',
        ],

        $p->status === 'draft' => [
            'Lanjutkan Draft',
            'Lengkapi dan kirim pengajuan',
            route('user.pendaftaran.edit', $p->id),
            'amber',
        ],

        $p->status === 'revisi' => [
            'Perbaiki Pengajuan',
            'Lengkapi revisi dari admin',
            route('user.pendaftaran.edit', $p->id),
            'orange',
        ],

        $p->statusSertifikat() === 'diterbitkan' => [
            'Unduh Sertifikat',
            'Sertifikat telah diterbitkan',
            route('user.pendaftaran.sertifikat', [
                'pendaftaran' => $p->id,
                'unduh' => 1,
            ]),
            'emerald',
        ],

        $p->statusSertifikat() === 'diproses' => [
            'Sertifikat sedang diproses',
            'Tombol unduh muncul setelah diterbitkan',
            route('user.pendaftaran.status'),
            'amber',
        ],

        in_array($p->status, ['ditolak', 'selesai'], true) => [
            'Daftar Magang Lagi',
            'Buat pengajuan baru',
            route('user.pendaftaran.create'),
            'blue',
        ],

        $p->statusSurat() === 'diterbitkan' => [
            'Unduh Surat Balasan',
            'Surat balasan telah diterbitkan',
            route('user.pendaftaran.surat', [
                'pendaftaran' => $p->id,
                'unduh' => 1,
            ]),
            'emerald',
        ],

        $p->statusSurat() === 'diproses' => [
            'Surat balasan sedang diproses',
            'Tombol unduh muncul setelah diterbitkan',
            route('user.pendaftaran.status'),
            'amber',
        ],

        default => [
            'Cek Pengajuan',
            'Lihat perkembangan pengajuan',
            route('user.pendaftaran.status'),
            'blue',
        ],
    };

    [$garis, $tombol] = [
        'blue' => [
            'hover:border-blue-300 dark:hover:border-blue-500/50',
            'bg-blue-600',
        ],
        'amber' => [
            'hover:border-amber-300 dark:hover:border-amber-500/50',
            'bg-amber-500',
        ],
        'orange' => [
            'hover:border-orange-300 dark:hover:border-orange-500/50',
            'bg-orange-500',
        ],
        'emerald' => [
            'hover:border-emerald-300 dark:hover:border-emerald-500/50',
            'bg-emerald-600',
        ],
    ][$aksi[3]];
@endphp

<div class="mt-4 sm:mt-5">
    <a href="{{ $aksi[2] }}"
       class="group w-full flex items-center justify-between gap-3 bg-white dark:bg-slate-800
              border border-slate-200 dark:border-slate-700 {{ $garis }}
              hover:shadow-md dark:hover:shadow-none rounded-xl px-4 py-3.5 transition">

        <div>
            <p class="text-sm font-bold text-slate-800 dark:text-slate-100">
                {{ $aksi[0] }}
            </p>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                {{ $aksi[1] }}
            </p>
        </div>

        <div class="w-9 h-9 rounded-lg {{ $tombol }} text-white flex items-center justify-center shrink-0 group-hover:translate-x-0.5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </div>
    </a>
</div>
</section>


{{-- Menu Cepat --}}

<section class="px-4 sm:px-6 mt-8 sm:mt-10">
    <div class="max-w-6xl mx-auto">

        <div class="flex items-end justify-between gap-4 mb-4 sm:mb-5">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                    Navigasi
                </p>
                <h2 class="text-lg sm:text-2xl font-extrabold text-slate-800 dark:text-slate-100 mt-1">
                    Menu Cepat
                </h2>
            </div>
            <span class="hidden sm:block text-xs text-slate-400 dark:text-slate-500">
                Akses fitur SiMagang
            </span>
        </div>

        {{-- Semua kartu memakai pola yang sama: chip netral, aksen biru hanya saat hover --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

            {{-- Daftar --}}
            <a href="{{ route('user.pendaftaran.create') }}"
               class="group relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl sm:rounded-3xl p-4 sm:p-5
                      hover:border-blue-300 dark:hover:border-blue-500/50
                      hover:shadow-lg hover:shadow-blue-100/60 dark:hover:shadow-none transition overflow-hidden">
                <div class="absolute right-0 top-0 w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-bl-[40px] opacity-60"></div>
                <div class="relative">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300
                                group-hover:bg-blue-600 group-hover:text-white transition-colors flex items-center justify-center mb-3 sm:mb-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-slate-800 dark:text-slate-100">Daftar Magang</h3>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Ajukan pendaftaran baru.</p>
                    <div class="mt-3 text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Status --}}
            <a href="{{ route('user.pendaftaran.status') }}"
               class="group relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl sm:rounded-3xl p-4 sm:p-5
                      hover:border-blue-300 dark:hover:border-blue-500/50
                      hover:shadow-lg hover:shadow-blue-100/60 dark:hover:shadow-none transition overflow-hidden">
                <div class="absolute right-0 top-0 w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-bl-[40px] opacity-60"></div>
                <div class="relative">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300
                                group-hover:bg-blue-600 group-hover:text-white transition-colors flex items-center justify-center mb-3 sm:mb-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-slate-800 dark:text-slate-100">Status Pengajuan</h3>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Pantau proses pendaftaran.</p>
                    <div class="mt-3 text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Informasi --}}
            <a href="{{ route('informasi') }}"
               class="group relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl sm:rounded-3xl p-4 sm:p-5
                      hover:border-blue-300 dark:hover:border-blue-500/50
                      hover:shadow-lg hover:shadow-blue-100/60 dark:hover:shadow-none transition overflow-hidden">
                <div class="absolute right-0 top-0 w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-bl-[40px] opacity-60"></div>
                <div class="relative">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300
                                group-hover:bg-blue-600 group-hover:text-white transition-colors flex items-center justify-center mb-3 sm:mb-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-slate-800 dark:text-slate-100">Informasi Magang</h3>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Lihat panduan dan ketentuan.</p>
                    <div class="mt-3 text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- Instansi --}}
            <a href="#instansi"
               class="group relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl sm:rounded-3xl p-4 sm:p-5
                      hover:border-blue-300 dark:hover:border-blue-500/50
                      hover:shadow-lg hover:shadow-blue-100/60 dark:hover:shadow-none transition overflow-hidden">
                <div class="absolute right-0 top-0 w-16 h-16 bg-slate-100 dark:bg-slate-800/60 rounded-bl-[40px] opacity-60"></div>
                <div class="relative">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300
                                group-hover:bg-blue-600 group-hover:text-white transition-colors flex items-center justify-center mb-3 sm:mb-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-slate-800 dark:text-slate-100">Instansi Tersedia</h3>
                    <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Lihat pilihan instansi magang.</p>
                    <div class="mt-3 text-blue-600 dark:text-blue-400 opacity-0 group-hover:opacity-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </div>
            </a>

        </div>

    </div>
</section>


{{-- Instansi Tersedia --}}
<section id="instansi" class="px-4 sm:px-6 mt-8 sm:mt-12">
    <div class="max-w-6xl mx-auto">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-4 sm:mb-5">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">
                    Instansi Tujuan
                </p>
                <h2 class="text-lg sm:text-2xl font-extrabold text-slate-800 dark:text-slate-100 mt-1">
                    Instansi yang Tersedia
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Pilihan instansi yang saat ini membuka pendaftaran melalui sistem.
                </p>
            </div>

            <div class="inline-flex items-center self-start sm:self-auto gap-2 px-3 py-1.5 rounded-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 dark:text-slate-400">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                {{ $dinases->count() }} instansi aktif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
            @forelse ($dinases as $dinas)

                <div class="group bg-white dark:bg-slate-900
                            border border-slate-200 dark:border-slate-800
                            rounded-2xl p-4
                            hover:border-blue-200 dark:hover:border-blue-500/40
                            hover:shadow-md hover:shadow-blue-100/40 dark:hover:shadow-none
                            transition-all duration-200">
                    <div class="flex items-start gap-3">

                        {{-- Ikon --}}
                        <div class="w-9 h-9 shrink-0 rounded-xl
                                    bg-slate-100 dark:bg-slate-800
                                    text-slate-600 dark:text-slate-300
                                    group-hover:bg-blue-50 dark:group-hover:bg-blue-500/10
                                    group-hover:text-blue-600 dark:group-hover:text-blue-400
                                    transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M12 9h.01M15 9h.01"/>
                            </svg>
                        </div>

                        {{-- Informasi instansi --}}
                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-sm text-slate-800 dark:text-slate-100 leading-snug line-clamp-2
                                       group-hover:text-blue-700 dark:group-hover:text-blue-300 transition-colors">
                                {{ $dinas->nama_dinas }}
                            </h3>

                            @if ($dinas->deskripsi)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed line-clamp-2">
                                    {{ $dinas->deskripsi }}
                                </p>
                            @else
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">
                                    Informasi instansi tersedia melalui sistem.
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Tombol daftar --}}
                    <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ route('user.pendaftaran.create') }}"
                           class="inline-flex items-center gap-1 text-xs font-bold
                                  text-blue-600 dark:text-blue-400
                                  hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                            Daftar Sekarang
                            <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:translate-x-0.5"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

            @empty

                <div class="col-span-full bg-white dark:bg-slate-900
                            border border-dashed border-slate-300 dark:border-slate-700
                            rounded-2xl p-8 text-center">
                    <div class="w-11 h-11 mx-auto rounded-xl bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-sm text-slate-700 dark:text-slate-200">Belum ada instansi tersedia</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Silakan cek kembali nanti.</p>
                </div>

            @endforelse
        </div>

    </div>
</section>

</div>

@if ($pendaftaran?->status === 'diterima')
    @php
        $suratTerbit = $pendaftaran->suratTerbit();
        $periodeMagang = ($pendaftaran->tanggal_mulai && $pendaftaran->tanggal_selesai)
            ? \Carbon\Carbon::parse($pendaftaran->tanggal_mulai)->translatedFormat('d M Y')
              . ' – '
              . \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->translatedFormat('d M Y')
            : 'Belum ditentukan';
    @endphp

    <style>
       
.pop-overlay {
    position: fixed;
    inset: 0;
    z-index: 100;

    display: none;
    align-items: center;
    justify-content: center;

    padding: 1rem;
    background: rgba(5, 12, 28, 0.68);

    -webkit-backdrop-filter: blur(10px);
    backdrop-filter: blur(10px);
}

/* Overlay ditampilkan ketika modal dibuka */
.pop-overlay.terbuka {
    display: flex;
    animation: pop-fade 0.25s ease-out;
}

.pop-card {
    width: 100%;
    max-width: 27rem;
    max-height: calc(100vh - 2rem);

    overflow-y: auto;
    border-radius: 1.75rem;

    background: #ffffff;
    color: #334155;

    box-shadow: 0 30px 80px -20px rgba(0, 0, 0, 0.45);

    animation: pop-masuk 0.4s cubic-bezier(0.2, 0.9, 0.3, 1.15);
}

/* Animasi overlay */
@keyframes pop-fade {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Animasi modal */
@keyframes pop-masuk {
    from {
        opacity: 0;
        transform: translateY(18px) scale(0.96);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Dukungan perangkat dengan layar kecil */
@media (max-width: 480px) {
    .pop-card {
        max-width: 100%;
        border-radius: 1.5rem;
    }
}

/* Menghormati preferensi pengurangan animasi */
@media (prefers-reduced-motion: reduce) {
    .pop-overlay.terbuka,
    .pop-card {
        animation: none;
    }
}

        
/* =========================================================
   POPUP PENDAFTARAN DITERIMA
   Modern, Minimalis, Responsive, Light & Dark Mode
========================================================= */

/* Overlay */
.pop-overlay {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;

    background: rgba(15, 23, 42, .58);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);

    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity .25s ease, visibility .25s ease;
}

.pop-overlay.terbuka {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    animation: pop-fade .25s ease;
}

/* Popup Card */
.pop-card {
    width: 100%;
    max-width: 460px;
    overflow: hidden;
    border-radius: 1.5rem;

    background: #ffffff;
    color: #1e293b;

    border: 1px solid #e2e8f0;
    box-shadow: 0 25px 70px -20px rgba(15, 23, 42, .35);

    transform: translateY(14px) scale(.97);
    transition: background .25s ease, color .25s ease;
}

.pop-overlay.terbuka .pop-card {
    animation: pop-masuk .35s cubic-bezier(.2, .8, .2, 1) forwards;
}

/* Header */
.pop-hero {
    position: relative;
    padding: 1.7rem 1.5rem 1.4rem;
    text-align: center;

    background: #ecfdf5;
    border-bottom: 1px solid #d1fae5;
}

/* Dekorasi lingkaran */
.pop-hero::before,
.pop-hero::after {
    content: "";
    position: absolute;
    border-radius: 9999px;
    background: rgba(16, 185, 129, .08);
    pointer-events: none;
}

.pop-hero::before {
    width: 90px;
    height: 90px;
    top: -40px;
    left: -30px;
}

.pop-hero::after {
    width: 65px;
    height: 65px;
    right: -20px;
    bottom: -25px;
}

/* Icon */
.pop-ikon {
    position: relative;
    z-index: 1;

    width: 4rem;
    height: 4rem;
    margin: 0 auto .9rem;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 2rem;
    border-radius: 1.25rem;

    color: #059669;
    background: #d1fae5;
    border: 1px solid #a7f3d0;
}

/* Label */
.pop-label {
    position: relative;
    z-index: 1;

    display: inline-block;
    padding: .4rem .85rem;
    border-radius: 9999px;

    font-size: .67rem;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;

    color: #047857;
    background: #d1fae5;
    border: 1px solid #a7f3d0;
}

/* Content */
.pop-isi {
    padding: 1.6rem 1.5rem 1.5rem;
    text-align: center;
}

.pop-judul {
    margin: 0;

    font-size: 1.4rem;
    line-height: 1.3;
    font-weight: 800;
    letter-spacing: -.025em;

    color: #0f172a;
}

.pop-dinas {
    margin: .55rem 0 0;

    font-size: .9rem;
    line-height: 1.6;

    color: #64748b;
}

.pop-dinas strong {
    color: #059669;
    font-weight: 700;
}

/* Information */
.pop-info {
    display: grid;
    gap: .7rem;
    margin: 1.35rem 0 0;
    text-align: left;
}

.pop-baris {
    display: flex;
    align-items: center;
    gap: .85rem;

    padding: .85rem 1rem;
    border-radius: .9rem;

    background: #f8fafc;
    border: 1px solid #e2e8f0;

    transition: background .2s ease, border-color .2s ease;
}

.pop-baris .ikon {
    width: 2.5rem;
    height: 2.5rem;
    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 1.15rem;
    border-radius: .75rem;

    background: #e2e8f0;
}

.pop-baris dt {
    font-size: .64rem;
    font-weight: 800;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: #94a3b8;
}

.pop-baris dd {
    margin: .2rem 0 0;

    font-size: .86rem;
    line-height: 1.4;
    font-weight: 700;

    color: #1e293b;
}

/* Status surat: sudah terbit */
.pop-baris.hijau {
    background: #ecfdf5;
    border-color: #a7f3d0;
}

.pop-baris.hijau .ikon {
    background: #d1fae5;
}

.pop-baris.hijau dd {
    color: #047857;
}

/* Status surat: sedang diproses */
.pop-baris.kuning {
    background: #fffbeb;
    border-color: #fde68a;
}

.pop-baris.kuning .ikon {
    background: #fef3c7;
}

.pop-baris.kuning dd {
    color: #b45309;
}

/* Buttons */
.pop-aksi {
    display: flex;
    gap: .65rem;
    margin-top: 1.4rem;
}

.pop-tombol {
    flex: 1;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-height: 48px;
    padding: .8rem 1rem;

    border: 1px solid transparent;
    border-radius: .85rem;
    cursor: pointer;

    font: inherit;
    font-size: .88rem;
    font-weight: 750;
    text-decoration: none;

    transition:
        transform .2s ease,
        box-shadow .2s ease,
        background .2s ease,
        border-color .2s ease;
}

/* Primary Button */
.pop-tombol.utama {
    color: #ffffff;
    background: #059669;
    box-shadow: 0 5px 14px rgba(5, 150, 105, .18);
}

.pop-tombol.utama:hover {
    background: #047857;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(5, 150, 105, .25);
}

/* Secondary Button */
.pop-tombol.sekunder {
    color: #475569;
    background: #f1f5f9;
    border-color: #e2e8f0;
}

.pop-tombol.sekunder:hover {
    background: #e2e8f0;
    transform: translateY(-2px);
}

/* Focus accessibility */
.pop-tombol:focus-visible {
    outline: 3px solid rgba(16, 185, 129, .35);
    outline-offset: 3px;
}

/* =========================================================
   DARK MODE
========================================================= */

.dark .pop-overlay {
    background: rgba(2, 6, 23, .76);
}

.dark .pop-card {
    background: #111c32;
    color: #e2e8f0;
    border-color: #263750;
    box-shadow: 0 25px 70px -20px rgba(0, 0, 0, .65);
}

.dark .pop-hero {
    background: #142b35;
    border-bottom-color: #254b48;
}

.dark .pop-hero::before,
.dark .pop-hero::after {
    background: rgba(52, 211, 153, .07);
}

.dark .pop-ikon {
    color: #6ee7b7;
    background: rgba(16, 185, 129, .14);
    border-color: rgba(52, 211, 153, .25);
}

.dark .pop-label {
    color: #6ee7b7;
    background: rgba(16, 185, 129, .13);
    border-color: rgba(52, 211, 153, .25);
}

.dark .pop-judul {
    color: #f8fafc;
}

.dark .pop-dinas {
    color: #94a3b8;
}

.dark .pop-dinas strong {
    color: #6ee7b7;
}

.dark .pop-baris {
    background: #0c1729;
    border-color: #263750;
}

.dark .pop-baris .ikon {
    background: #1e3048;
}

.dark .pop-baris dt {
    color: #8495ac;
}

.dark .pop-baris dd {
    color: #e2e8f0;
}

.dark .pop-baris.hijau {
    background: rgba(16, 185, 129, .09);
    border-color: rgba(52, 211, 153, .25);
}

.dark .pop-baris.hijau .ikon {
    background: rgba(16, 185, 129, .16);
}

.dark .pop-baris.hijau dd {
    color: #6ee7b7;
}

.dark .pop-baris.kuning {
    background: rgba(245, 158, 11, .09);
    border-color: rgba(245, 158, 11, .25);
}

.dark .pop-baris.kuning .ikon {
    background: rgba(245, 158, 11, .15);
}

.dark .pop-baris.kuning dd {
    color: #fcd34d;
}

.dark .pop-tombol.utama {
    color: #052e24;
    background: #34d399;
    box-shadow: 0 5px 16px rgba(16, 185, 129, .12);
}

.dark .pop-tombol.utama:hover {
    background: #6ee7b7;
}

.dark .pop-tombol.sekunder {
    color: #cbd5e1;
    background: #1e3048;
    border-color: #30445f;
}

.dark .pop-tombol.sekunder:hover {
    background: #293e58;
}

/* =========================================================
   ANIMATION & RESPONSIVE
========================================================= */

@keyframes pop-fade {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes pop-masuk {
    from {
        opacity: 0;
        transform: translateY(14px) scale(.97);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@media (max-width: 480px) {
    .pop-overlay {
        padding: .85rem;
    }

    .pop-card {
        max-width: 100%;
        border-radius: 1.25rem;
    }

    .pop-hero {
        padding: 1.4rem 1rem 1.2rem;
    }

    .pop-isi {
        padding: 1.3rem 1.1rem 1.2rem;
    }

    .pop-judul {
        font-size: 1.2rem;
    }

    .pop-dinas {
        font-size: .84rem;
    }

    .pop-baris {
        padding: .75rem;
    }

    .pop-tombol {
        padding: .75rem .6rem;
        font-size: .82rem;
    }
}

@media (prefers-reduced-motion: reduce) {
    .pop-overlay,
    .pop-card,
    .pop-tombol {
        animation: none !important;
        transition: none !important;
    }
}

    </style>

    <div id="modalDiterima" class="pop-overlay" role="dialog" aria-modal="true" aria-labelledby="judulDiterima">
        <div class="pop-card">

            <div class="pop-hero">
                <div class="pop-ikon">✓</div>
                <span class="pop-label">Pendaftaran Diterima</span>
            </div>

            <div class="pop-isi">
    <h2 id="judulDiterima" class="pop-judul">
        Selamat, kamu diterima magang!
    </h2>

    <p class="pop-dinas">
        Pendaftaranmu di <strong>{{ $pendaftaran->dinas_tujuan }}</strong> telah disetujui.
    </p>

    @if ($pendaftaran->tipe === 'kelompok')
        <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">
            Kelompok · {{ $pendaftaran->anggotas->count() + 1 }} orang
        </p>
    @endif

    <dl class="pop-info">
                    <div class="pop-baris">
                        <span class="ikon">📅</span>
                        <div>
                            <dt>Periode magang</dt>
                            <dd>{{ $periodeMagang }}</dd>
                        </div>
                    </div>

                    <div class="pop-baris {{ $suratTerbit ? 'hijau' : 'kuning' }}">
                        <span class="ikon">{{ $suratTerbit ? '✅' : '⏳' }}</span>
                        <div>
                            <dt>Surat balasan</dt>
                            <dd>{{ $suratTerbit ? 'Telah diterbitkan, siap diunduh' : 'Sedang diproses' }}</dd>
                        </div>
                    </div>
                </dl>

                <div class="pop-aksi">
                    <a id="popLihatStatus" href="{{ route('user.pendaftaran.status') }}" class="pop-tombol utama">Lihat Status</a>
                    <button type="button" id="tutupDiterima" class="pop-tombol sekunder">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tampil sekali per pendaftaran di browser ini.
        (() => {
            const kunci = 'diterima-dilihat-{{ $pendaftaran->id }}-{{ $suratTerbit ? 'terbit' : 'proses' }}';
            const overlay = document.getElementById('modalDiterima');

            try {
                if (localStorage.getItem(kunci)) return;
                localStorage.setItem(kunci, '1');
            } catch (e) {}

            const buka = () => {
                overlay.classList.add('terbuka');
                document.documentElement.style.overflow = 'hidden';
                document.getElementById('popLihatStatus').focus();
            };
            const tutup = () => {
                overlay.classList.remove('terbuka');
                document.documentElement.style.overflow = '';
            };

            document.getElementById('tutupDiterima').addEventListener('click', tutup);
            overlay.addEventListener('click', e => { if (e.target === overlay) tutup(); });
            document.addEventListener('keydown', e => { if (e.key === 'Escape') tutup(); });

            buka();
        })();
    </script>
@endif
@endsection