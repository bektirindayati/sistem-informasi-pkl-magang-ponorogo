{{-- Komponen: <x-navbar /> --}}

<div class="fixed top-0 left-0 right-0 z-50 px-3 sm:px-4 pt-3 sm:pt-4">

    <nav class="max-w-6xl mx-auto bg-white/90 backdrop-blur-md border border-slate-200/80 rounded-2xl shadow-sm transition-all duration-300">

        <div class="px-3 sm:px-5 py-2.5 flex items-center justify-between gap-3">

            {{-- LOGO --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 sm:gap-3 group shrink-0">

                <div class="h-9 w-9 sm:h-12 sm:w-12 rounded-full bg-gradient-to-br from-blue-600 to-cyan-600 flex items-center justify-center text-white font-black text-lg sm:text-2xl shadow-md group-hover:scale-105 transition-transform shrink-0">
                    M
                </div>

                <div class="flex flex-col">
                    <span class="text-[15px] sm:text-lg font-extrabold text-slate-900 leading-tight tracking-tight">
                        MagangHub
                    </span>

                    <span class="hidden sm:block text-[10px] sm:text-xs text-blue-600 font-semibold">
                        Portal Pendaftaran Magang Terpadu
                    </span>
                </div>
            </a>


            {{-- BAGIAN KANAN --}}
            <div class="flex items-center justify-end gap-2 shrink-0">

                {{--
                    TOMBOL AI — HANYA di halaman utama ("/").
                    Kalau route halaman utama kamu punya nama (mis. route('home')),
                    lebih aman pakai: @if(request()->routeIs('home'))
                --}}
                @if (true)
                    <button
                        type="button"
                        onclick="toggleAiChatbot()"
                        class="relative w-9 h-9 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl
                               bg-gradient-to-br from-indigo-600 to-blue-600
                               hover:from-indigo-700 hover:to-blue-700
                               text-white flex items-center justify-center
                               shadow-md shadow-indigo-500/25 transition-all duration-200 shrink-0"
                        title="Tanya Asisten AI MagangHub"
                    >
                        <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-300 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-400 border-2 border-white"></span>
                        </span>

                        <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M12 8V4H8M12 8h4a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6a2 2 0 012-2h4z" />
                            <circle cx="9" cy="13" r="1" fill="currentColor" stroke="none" />
                            <circle cx="15" cy="13" r="1" fill="currentColor" stroke="none" />
                        </svg>
                    </button>

                    <x-ai-chatbot-modal />
                @endif

                @auth

                    {{-- DASHBOARD --}}
                    @if (Auth::user()->isAdmin())

                        <a href="{{ route('admin.dashboard') }}"
                           class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-xl transition">
                            Dashboard
                        </a>

                    @else

                        <a href="{{ route('user.dashboard') }}"
                           class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-xl transition">
                            Dashboard
                        </a>

                    @endif


                    {{-- PROFIL USER --}}
                    <div class="flex items-center gap-2 bg-slate-100/80 border border-slate-200 py-1 sm:py-1.5 px-2 sm:px-3 rounded-[14px] sm:rounded-2xl">

                        <div class="flex items-center gap-2.5">

                            @if (Auth::user()->avatar)

                                <img
                                    src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                    alt="Avatar"
                                    class="w-8 h-8 sm:w-9 sm:h-9 rounded-full object-cover border border-blue-200 shrink-0"
                                >

                            @else

                                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs shrink-0">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>

                            @endif


                            <div class="hidden sm:flex flex-col text-left">

                                <span class="text-xs font-bold text-slate-800 leading-tight">
                                    {{ Auth::user()->name }}
                                </span>

                                <span class="text-[10px] text-blue-600 font-semibold">
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
                                class="p-1.5 sm:p-2 rounded-lg sm:rounded-xl bg-white hover:bg-red-50 text-slate-500 hover:text-red-600 border border-slate-200 transition shadow-xs flex items-center justify-center"
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