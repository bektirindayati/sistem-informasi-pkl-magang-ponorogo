{{-- resources/views/components/user-navbar.blade.php --}}

@php
    $isDokumentasi = request()->routeIs('dokumentasi.show');

    // Kelas link navigasi (desktop & mobile) dikumpulkan di sini supaya tidak berulang
    $aktif    = 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-300';
    $nonaktif = 'text-slate-600 hover:bg-slate-50 hover:text-blue-600 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-blue-400';
    $nonaktifMobile = 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800';
@endphp


<div class="fixed top-0 left-0 right-0 z-50 px-4 sm:px-6 pt-3 sm:pt-4">

    <nav class="w-full max-w-6xl mx-auto bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border border-slate-200/80 dark:border-slate-700/80 rounded-2xl shadow-sm transition-all duration-300">


        <div class="h-16 px-4 sm:px-5 flex items-center justify-between gap-3">

            {{-- LOGO --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 sm:gap-3 group shrink-0">

                {{-- Logo SIMANTARA (kotak tetap putih agar logo ber-latar putih tetap rapi di mode gelap) --}}
                <div
                    class="h-9 w-9 sm:h-12 sm:w-12
                           rounded-xl
                           bg-white
                           border border-slate-200 dark:border-slate-600
                           flex items-center justify-center
                           overflow-hidden
                           shadow-sm
                           group-hover:scale-105
                           transition-transform
                           shrink-0"
                >
                    <img
    src="{{ asset('images/logo-simantara.png') }}"
    alt="Logo SIMANTARA"
    class="w-full h-full object-contain"
>
                </div>

                {{-- Nama aplikasi --}}
                <div class="flex flex-col">
                    <span
                        class="text-[15px] sm:text-lg
                               font-extrabold
                               text-slate-900 dark:text-white
                               leading-tight
                               tracking-tight"
                    >
                        SIMANTARA
                    </span>

                    <span
                        class="hidden sm:block
                               text-[10px] sm:text-xs
                               text-blue-600 dark:text-blue-400
                               font-semibold"
                    >
                        Sistem Informasi Magang Terintegrasi
                    </span>
                </div>
            </a>

            {{-- =========================================================
                 NAVBAR KHUSUS DOKUMENTASI PUBLIK
                 ========================================================= --}}
            @if ($isDokumentasi)

                <div class="flex items-center gap-2 sm:gap-3">

                    {{-- Tombol mode gelap / terang --}}
                    <x-theme-toggle />

                    {{-- Masuk --}}
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center
                              px-3 sm:px-4 py-2 rounded-xl
                              text-sm font-bold
                              text-blue-600 bg-blue-50 hover:bg-blue-100
                              dark:text-blue-300 dark:bg-blue-500/15 dark:hover:bg-blue-500/25
                              transition">
                        Masuk
                    </a>

                </div>


            {{-- =========================================================
                 NAVBAR USER BIASA
                 ========================================================= --}}
            @else

                {{-- Desktop Navigation --}}
                <div class="hidden md:flex items-center gap-1">

                    <a href="{{ route('user.dashboard') }}"
                       class="px-4 py-2 rounded-xl text-sm font-bold transition
                       {{ request()->routeIs('user.dashboard') ? $aktif : $nonaktif }}">
                        Dashboard
                    </a>

                    <a href="{{ route('user.pendaftaran.status') }}"
                       class="px-4 py-2 rounded-xl text-sm font-bold transition
                       {{ request()->routeIs('user.pendaftaran.status') ? $aktif : $nonaktif }}">
                        Status Pengajuan
                    </a>

                    <a href="{{ route('user.pendaftaran.riwayat') }}"
                       class="px-4 py-2 rounded-xl text-sm font-bold transition
                       {{ request()->routeIs('user.pendaftaran.riwayat') ? $aktif : $nonaktif }}">
                        Riwayat
                    </a>

                </div>


                {{-- User Menu --}}
                <div class="flex items-center gap-2 sm:gap-3">

                    {{-- Tombol mode gelap / terang --}}
                    <x-theme-toggle />

                    @auth

                        {{-- Profil --}}
                        <a href="{{ route('user.profil') }}"
                           class="hidden sm:flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition">

                            @if(Auth::user()->avatar)

                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                     alt="Avatar"
                                     class="w-8 h-8 rounded-full object-cover border border-slate-200 dark:border-slate-600">

                            @else

                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-cyan-500 text-white flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>

                            @endif

                            <div class="hidden lg:block text-left leading-tight">

                                <div class="text-xs font-bold text-slate-800 dark:text-slate-100 max-w-[140px] truncate">
                                    {{ Auth::user()->name }}
                                </div>

                                <div class="text-[10px] text-slate-400 dark:text-slate-500">
                                    Pengguna
                                </div>

                            </div>

                        </a>


                        {{-- Logout --}}
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf

                            <button type="submit"
                                    class="hidden sm:flex items-center justify-center w-9 h-9 rounded-xl
                                           text-slate-400 hover:text-rose-600 hover:bg-rose-50
                                           dark:hover:text-rose-400 dark:hover:bg-rose-500/10 transition"
                                    title="Keluar">

                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>

                            </button>
                        </form>

                    @else

                        {{-- Guest --}}
                        <a href="{{ route('login') }}"
                           class="hidden sm:inline-flex items-center justify-center
                                  px-4 py-2 rounded-xl
                                  text-sm font-bold text-blue-600 bg-blue-50 hover:bg-blue-100
                                  dark:text-blue-300 dark:bg-blue-500/15 dark:hover:bg-blue-500/25
                                  transition">
                            Masuk
                        </a>

                    @endauth


                    {{-- Mobile Menu Button --}}
                    <button type="button"
                            id="user-mobile-menu-button"
                            class="md:hidden w-9 h-9 rounded-xl flex items-center justify-center
                                   text-slate-600 hover:bg-slate-100
                                   dark:text-slate-300 dark:hover:bg-slate-800
                                   transition shrink-0">

                        <svg id="user-mobile-menu-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>

                        <svg id="user-mobile-menu-close" class="hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 6l12 12M6 18L18 6"/>
                        </svg>

                    </button>

                </div>

            @endif

        </div>


        {{-- =========================================================
             MOBILE NAVIGATION
             Tidak ditampilkan pada halaman Dokumentasi
             karena navbar Dokumentasi sudah sederhana.
             ========================================================= --}}
        @if (!$isDokumentasi)

            <div id="user-mobile-menu"
                 class="hidden md:hidden border-t border-slate-100 dark:border-slate-700">

                <div class="px-4 py-4 space-y-1">

                    <a href="{{ route('user.dashboard') }}"
                       class="block px-4 py-3 rounded-xl text-sm font-bold
                       {{ request()->routeIs('user.dashboard') ? $aktif : $nonaktifMobile }}">
                        Dashboard
                    </a>

                    <a href="{{ route('user.pendaftaran.status') }}"
                       class="block px-4 py-3 rounded-xl text-sm font-bold
                       {{ request()->routeIs('user.pendaftaran.status') ? $aktif : $nonaktifMobile }}">
                        Status Pengajuan
                    </a>

                    <a href="{{ route('user.pendaftaran.riwayat') }}"
                       class="block px-4 py-3 rounded-xl text-sm font-bold
                       {{ request()->routeIs('user.pendaftaran.riwayat') ? $aktif : $nonaktifMobile }}">
                        Riwayat
                    </a>

                    <a href="{{ route('user.profil') }}"
                       class="block px-4 py-3 rounded-xl text-sm font-bold
                       {{ request()->routeIs('user.profil') ? $aktif : $nonaktifMobile }}">
                        Profil Saya
                    </a>

                    <div class="pt-2 border-t border-slate-100 dark:border-slate-700">

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button type="submit"
                                    class="w-full text-left px-4 py-3 rounded-xl text-sm font-bold
                                           text-rose-600 hover:bg-rose-50
                                           dark:text-rose-400 dark:hover:bg-rose-500/10 transition">
                                Keluar
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        @endif

    </nav>

</div>


@once
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const button = document.getElementById('user-mobile-menu-button');
        const menu = document.getElementById('user-mobile-menu');
        const openIcon = document.getElementById('user-mobile-menu-open');
        const closeIcon = document.getElementById('user-mobile-menu-close');

        if (button && menu) {

            button.addEventListener('click', function () {

                menu.classList.toggle('hidden');

                if (openIcon) {
                    openIcon.classList.toggle('hidden');
                }

                if (closeIcon) {
                    closeIcon.classList.toggle('hidden');
                }

            });

        }

    });
</script>
@endonce