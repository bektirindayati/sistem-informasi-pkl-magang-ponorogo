{{-- resources/views/components/user-navbar.blade.php --}}

<div class="fixed top-0 left-0 right-0 z-50 px-3 sm:px-4 pt-3 sm:pt-4">

    <nav class="max-w-7xl mx-auto bg-white/90 backdrop-blur-md border border-slate-200/80 rounded-2xl shadow-sm transition-all duration-300">

        <div class="h-16 px-4 sm:px-5 flex items-center justify-between gap-3">

            {{-- Logo --}}
            <a href="{{ route('user.dashboard') }}"
               class="flex items-center gap-2.5 sm:gap-3 group shrink-0">

                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 6v12m-6-6h12"/>
                    </svg>
                </div>

                <div class="block leading-tight">
                    <div class="font-extrabold text-slate-900 text-sm tracking-tight">
                        SiMagang
                    </div>
                    <div class="text-[10px] text-blue-600 font-semibold">
                        Kabupaten Ponorogo
                    </div>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex items-center gap-1">

                <a href="{{ route('user.dashboard') }}"
                   class="px-4 py-2 rounded-xl text-sm font-bold transition
                   {{ request()->routeIs('user.dashboard')
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' }}">
                    Dashboard
                </a>

                <a href="{{ route('user.pendaftaran.status') }}"
                   class="px-4 py-2 rounded-xl text-sm font-bold transition
                   {{ request()->routeIs('user.pendaftaran.status')
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' }}">
                    Status Pengajuan
                </a>

                <a href="{{ route('user.pendaftaran.riwayat') }}"
                   class="px-4 py-2 rounded-xl text-sm font-bold transition
                   {{ request()->routeIs('user.pendaftaran.riwayat')
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-slate-600 hover:bg-slate-50 hover:text-blue-600' }}">
                    Riwayat
                </a>
            </div>

           {{-- User Menu --}}
<div class="flex items-center gap-2 sm:gap-3">

    @auth
        {{-- Profil --}}
        <a href="{{ route('user.profil') }}"
           class="hidden sm:flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl hover:bg-slate-50 transition">

            @if(Auth::user()->avatar)
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                     alt="Avatar"
                     class="w-8 h-8 rounded-full object-cover border border-slate-200">
            @else
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 to-cyan-500 text-white flex items-center justify-center text-xs font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif

            <div class="hidden lg:block text-left leading-tight">
                <div class="text-xs font-bold text-slate-800 max-w-[140px] truncate">
                    {{ Auth::user()->name }}
                </div>
                <div class="text-[10px] text-slate-400">
                    Pengguna
                </div>
            </div>
        </a>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf

            <button type="submit"
                    class="hidden sm:flex items-center justify-center w-9 h-9 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition"
                    title="Keluar">

                <svg class="w-4 h-4"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
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
           class="hidden sm:inline-flex items-center justify-center px-4 py-2 rounded-xl
                  text-sm font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 transition">
            Masuk
        </a>

    @endauth

                {{-- Mobile Menu Button --}}
                <button type="button"
                        id="user-mobile-menu-button"
                        class="md:hidden w-9 h-9 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-100 transition shrink-0">

                    <svg id="user-mobile-menu-open"
                         class="w-5 h-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>

                    <svg id="user-mobile-menu-close"
                         class="hidden w-5 h-5"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 6l12 12M6 18L18 6"/>
                    </svg>
                </button>

            </div>
        </div>

        {{-- Mobile Navigation --}}
        <div id="user-mobile-menu"
             class="hidden md:hidden border-t border-slate-100">

            <div class="px-4 py-4 space-y-1">

                <a href="{{ route('user.dashboard') }}"
                   class="block px-4 py-3 rounded-xl text-sm font-bold
                   {{ request()->routeIs('user.dashboard')
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-slate-600 hover:bg-slate-50' }}">
                    Dashboard
                </a>

                <a href="{{ route('user.pendaftaran.status') }}"
                   class="block px-4 py-3 rounded-xl text-sm font-bold
                   {{ request()->routeIs('user.pendaftaran.status')
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-slate-600 hover:bg-slate-50' }}">
                    Status Pengajuan
                </a>

                <a href="{{ route('user.pendaftaran.riwayat') }}"
                   class="block px-4 py-3 rounded-xl text-sm font-bold
                   {{ request()->routeIs('user.pendaftaran.riwayat')
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-slate-600 hover:bg-slate-50' }}">
                    Riwayat
                </a>

                <a href="{{ route('user.profil') }}"
                   class="block px-4 py-3 rounded-xl text-sm font-bold
                   {{ request()->routeIs('user.profil')
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-slate-600 hover:bg-slate-50' }}">
                    Profil Saya
                </a>

                <div class="pt-2 border-t border-slate-100">

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit"
                                class="w-full text-left px-4 py-3 rounded-xl text-sm font-bold text-rose-600 hover:bg-rose-50 transition">
                            Keluar
                        </button>
                    </form>

                </div>
            </div>
        </div>

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
                if (openIcon) openIcon.classList.toggle('hidden');
                if (closeIcon) closeIcon.classList.toggle('hidden');
            });
        }

    });
</script>
@endonce