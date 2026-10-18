{{-- Komponen: <x-navbar /> --}}

<div class="fixed top-0 left-0 right-0 z-50 px-4 sm:px-6 pt-3 sm:pt-4">

    <nav class="w-full max-w-6xl mx-auto bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border border-slate-200/80 dark:border-slate-700/80 rounded-2xl shadow-sm transition-all duration-300">

        <div class="px-3 sm:px-5 py-2.5 flex items-center justify-between gap-3">

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
                        src="{{ asset('images/logo-simantara.jpg') }}"
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


            {{-- BAGIAN KANAN --}}
            <div class="flex items-center justify-end gap-2 shrink-0">

                {{-- TOMBOL MODE GELAP / TERANG --}}
                <x-theme-toggle />

                {{-- ASISTEN AI --}}
                <x-ai-chatbot-launcher />

                @auth

                    {{-- DASHBOARD --}}
                    @if (Auth::user()->isAdmin())

                        <a href="{{ route('admin.dashboard') }}"
                           class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold
                                  text-blue-600 hover:text-blue-700 hover:bg-blue-50
                                  dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-500/10
                                  rounded-xl transition">
                            Dashboard
                        </a>

                    @else

                        <a href="{{ route('user.dashboard') }}"
                           class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold
                                  text-blue-600 hover:text-blue-700 hover:bg-blue-50
                                  dark:text-blue-400 dark:hover:text-blue-300 dark:hover:bg-blue-500/10
                                  rounded-xl transition">
                            Dashboard
                        </a>

                    @endif


                    {{-- PROFIL USER --}}
                    <div class="flex items-center gap-2
                                bg-slate-100/80 dark:bg-slate-800/80
                                border border-slate-200 dark:border-slate-700
                                py-1 sm:py-1.5 px-2 sm:px-3
                                rounded-[14px] sm:rounded-2xl">

                        <div class="flex items-center gap-2.5">

                            @if (Auth::user()->avatar)

                                <img
                                    src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                    alt="Avatar"
                                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover border border-blue-200 dark:border-blue-500/40 shrink-0"
                                >

                            @else

                                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs shrink-0">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>

                            @endif


                            <div class="hidden sm:flex flex-col text-left">

                                <span class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight">
                                    {{ Auth::user()->name }}
                                </span>

                                <span class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold">
                                    {{ Auth::user()->isAdmin() ? 'Administrator' : 'Peserta Magang' }}
                                </span>

                            </div>

                        </div>


                        {{-- LOGOUT --}}
                        <form action="{{ route('logout') }}" method="POST" class="inline ml-1 sm:ml-3 m-0 p-0">

                            @csrf

                            <button
                                type="submit"
                                title="Keluar"
                                class="p-1.5 sm:p-2 rounded-lg sm:rounded-xl
                                       bg-white hover:bg-red-50 text-slate-500 hover:text-red-600
                                       dark:bg-slate-900 dark:hover:bg-red-500/10 dark:text-slate-400 dark:hover:text-red-400
                                       border border-slate-200 dark:border-slate-700
                                       transition shadow-xs flex items-center justify-center"
                            >

                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                    />
                                </svg>
                            </button>
                        </form>
                    </div>

                @else

                    {{-- BELUM LOGIN --}}
                    <a
                        href="{{ route('login') }}"
                        class="text-center px-4 sm:px-6 py-2 sm:py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-bold rounded-lg sm:rounded-xl shadow-md shadow-blue-500/30 transition-all transform hover:-translate-y-0.5"
                    >
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </nav>
</div>