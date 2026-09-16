{{-- 
    Komponen: <x-topbar />
    Topbar admin
--}}

<header
    class="admin-topbar
           bg-white
           border-b border-slate-200
           px-4 md:px-8
           flex items-center justify-between
           sticky top-0
           z-30
           shadow-sm
           gap-2"
>

    {{-- =========================================================
        BAGIAN KIRI
        Tombol menu mobile + judul halaman
        ========================================================= --}}
    <div
        class="flex items-center gap-3
               min-w-0 flex-1"
    >

        {{-- Tombol buka sidebar mobile --}}
        <button
            id="openSidebarBtn"
            type="button"
            class="md:hidden
                   p-2
                   rounded-xl
                   bg-slate-100
                   hover:bg-slate-200
                   text-slate-700
                   transition
                   flex items-center justify-center
                   shrink-0"
        >
            <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                ></path>
            </svg>
        </button>


        {{-- Judul halaman --}}
        <h1
            class="text-base md:text-xl
                   font-extrabold
                   text-slate-900
                   truncate
                   min-w-0"
        >
            @yield('page-title', 'Dashboard Admin')
        </h1>

    </div>


    {{-- =========================================================
        BAGIAN KANAN
        Informasi admin + avatar
        ========================================================= --}}
    <div
        class="flex items-center
               gap-2.5
               shrink-0"
    >

        {{-- Informasi admin --}}
        <div
            class="text-right
                   hidden sm:block
                   max-w-[140px]
                   md:max-w-[220px]"
        >

            <p
                class="text-sm
                       font-bold
                       text-slate-900
                       leading-tight
                       truncate"
            >
                {{ Auth::user()->name ?? 'Admin' }}
            </p>

            <p
                class="text-[10px]
                       text-slate-500
                       font-semibold
                       uppercase
                       tracking-wider
                       truncate"
            >
                {{ Auth::user()->isSuperAdmin()
                    ? 'Super Admin'
                    : (Auth::user()->dinas->nama_dinas ?? 'Belum ditugaskan ke dinas') }}
            </p>

        </div>


        {{-- Avatar --}}
        <div
            class="w-9 h-9
                   md:w-10 md:h-10
                   rounded-full
                   bg-cyan-50
                   text-cyan-700
                   flex items-center justify-center
                   font-extrabold
                   text-xs md:text-sm
                   shrink-0"
        >
            {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
        </div>

    </div>

</header>