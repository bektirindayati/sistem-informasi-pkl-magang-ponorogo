
{{--
    Komponen: <x-sidebar />
    Sidebar admin SIMANTARA (desktop + mobile).
    Navigasi mengikuti permission Spatie.
    Mendukung light mode dan dark mode.
--}}

@php
    $navItems = [
        [
            'permission' => 'lihat-dashboard',
            'route' => 'admin.dashboard',
            'icon' => '📊',
            'label' => 'Dashboard',
        ],
        [
            'permission' => 'lihat-pendaftar',
            'route' => 'admin.pendaftar.index',
            'icon' => '📁',
            'label' => 'Data Pendaftar',
        ],
        [
            'permission' => 'verifikasi-pendaftar',
            'route' => 'admin.verifikasi.index',
            'icon' => '✓',
            'label' => 'Verifikasi Berkas',
        ],
        [
            'permission' => 'kelola-instansi',
            'route' => 'admin.instansi.index',
            'icon' => '🏛️',
            'label' => 'Kelola Instansi/Bidang',
        ],
        [
            'permission' => 'kelola-dokumentasi',
            'route' => 'admin.dokumentasi.index',
            'icon' => '📸',
            'label' => 'Kelola Dokumentasi',
        ],
    ];
@endphp

{{-- DESKTOP SIDEBAR --}}
<aside
    class="hidden md:flex h-screen w-64 shrink-0 flex-col
           bg-white dark:bg-[#0b1220]
           border-r border-slate-200 dark:border-slate-800
           text-slate-700 dark:text-slate-300
           shadow-xl shadow-slate-200/40 dark:shadow-black/20">

    
{{-- BRAND --}}
<div class="relative overflow-hidden
            border-b border-slate-200 dark:border-slate-800
            px-5 py-5">

    <div class="pointer-events-none absolute -right-8 -top-10 h-28 w-28
                rounded-full bg-blue-400/10 dark:bg-cyan-500/[0.08] blur-2xl">
    </div>

    <a href="{{ route('admin.dashboard') }}"
       class="group relative flex items-center gap-3">

        {{-- Logo SIMANTARA --}}
        <div class="h-11 w-11 shrink-0
                    rounded-2xl
                    bg-white
                    border border-slate-200 dark:border-slate-600
                    flex items-center justify-center
                    overflow-hidden
                    shadow-sm
                    group-hover:scale-105
                    transition-transform">

            <img
                src="{{ asset('images/logo-simantara.png') }}"
                alt="Logo SIMANTARA"
                class="w-full h-full object-contain"
            >
        </div>

        <div class="min-w-0">
            <h1 class="text-[17px] font-extrabold leading-tight
                       tracking-[0.12em] text-slate-900 dark:text-white">
                SIMANTARA
            </h1>

            <p class="mt-1 text-[9px] font-semibold uppercase
                      tracking-[0.17em] text-blue-700 dark:text-cyan-400">
                Panel Administrator
            </p>
        </div>

    </a>
</div>

    {{-- DESKTOP NAVIGATION --}}
    <nav class="flex-1 overflow-y-auto px-3 py-5
                [scrollbar-color:#cbd5e1_transparent]
                dark:[scrollbar-color:#334155_transparent]
                [scrollbar-width:thin]">

        <p class="mb-3 px-3 text-[10px] font-bold uppercase
                  tracking-[0.18em] text-slate-500 dark:text-slate-500">
            Menu Utama
        </p>

        <div class="space-y-1.5">
            @foreach ($navItems as $item)
                @can($item['permission'])
                    <x-admin.nav-link
                        :route="$item['route']"
                        :icon="$item['icon']"
                        :variant="$item['route'] === 'admin.dokumentasi.index'
                            ? 'highlight'
                            : 'default'">
                        {{ $item['label'] }}
                    </x-admin.nav-link>
                @endcan
            @endforeach
        </div>

        {{-- MANAJEMEN SISTEM --}}
        @canany(['kelola-dinas', 'kelola-admin'])
            <div class="mt-7 border-t border-slate-200 dark:border-slate-800 pt-5">

                <p class="mb-3 px-3 text-[10px] font-bold uppercase
                          tracking-[0.18em] text-slate-500">
                    Manajemen Sistem
                </p>

                <div class="space-y-1.5">
                    @can('kelola-dinas')
                        <x-admin.nav-link
                            route="admin.dinas.index"
                            icon="🏢">
                            Kelola Dinas
                        </x-admin.nav-link>
                    @endcan

                    @can('kelola-admin')
                        <x-admin.nav-link
                            route="admin.pengguna.index"
                            icon="👤">
                            Kelola Admin
                        </x-admin.nav-link>
                    @endcan
                </div>
            </div>
        @endcanany
    </nav>

    {{-- DESKTOP LOGOUT --}}
    <div class="shrink-0 border-t border-slate-200 dark:border-slate-800 p-3">

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit"
                class="group flex w-full items-center rounded-xl
                       px-3 py-3 text-left text-sm font-semibold
                       text-slate-600 dark:text-slate-400
                       transition-all duration-200
                       hover:bg-rose-50 hover:text-rose-700
                       dark:hover:bg-rose-500/10 dark:hover:text-rose-300
                       focus:outline-none focus:ring-2
                       focus:ring-rose-400/40">
                <span>Keluar Sistem</span>
            </button>
        </form>

        <div class="mt-3 flex items-center gap-2 px-3 pb-1">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500
                         dark:bg-emerald-400 shadow-sm
                         shadow-emerald-400/50">
            </span>

            <p class="text-[10px] tracking-wide text-slate-500 dark:text-slate-500">
                SIMANTARA · Kabupaten Ponorogo
            </p>
        </div>
    </div>
</aside>


{{-- MOBILE BACKDROP --}}
<div
    id="mobileSidebarBackdrop"
    class="fixed inset-0 z-40 hidden
           bg-slate-900/40 dark:bg-slate-950/70
           opacity-0 backdrop-blur-sm
           transition-opacity duration-300 md:hidden">
</div>


{{-- MOBILE DRAWER --}}
<aside
    id="mobileSidebar"
    class="fixed inset-y-0 left-0 z-50 flex h-screen
           w-72 max-w-[88vw] -translate-x-full flex-col
           bg-white dark:bg-[#0b1220]
           border-r border-slate-200 dark:border-slate-800
           text-slate-700 dark:text-slate-300
           shadow-2xl shadow-slate-900/10 dark:shadow-black/30
           transition-transform duration-300 ease-in-out md:hidden">

    
{{-- MOBILE BRAND --}}
<div class="relative flex shrink-0 items-center justify-between
            overflow-hidden
            border-b border-slate-200 dark:border-slate-800
            px-5 py-5">

    <div class="pointer-events-none absolute -right-8 -top-10 h-28 w-28
                rounded-full bg-blue-400/10 dark:bg-cyan-500/[0.08] blur-2xl">
    </div>

    <a href="{{ route('admin.dashboard') }}"
       class="group relative flex min-w-0 items-center gap-3">

        {{-- Logo SIMANTARA --}}
        <div class="h-11 w-11 shrink-0
                    rounded-2xl
                    bg-white
                    border border-slate-200 dark:border-slate-600
                    flex items-center justify-center
                    overflow-hidden
                    shadow-sm
                    group-hover:scale-105
                    transition-transform">

            <img
                src="{{ asset('images/logo-simantara.png') }}"
                alt="Logo SIMANTARA"
                class="w-full h-full object-contain"
            >
        </div>

        <div class="min-w-0">
            <h1 class="text-[17px] font-extrabold leading-tight
                       tracking-[0.12em] text-slate-900 dark:text-white">
                SIMANTARA
            </h1>

            <p class="mt-1 text-[9px] font-semibold uppercase
                      tracking-[0.17em] text-blue-700 dark:text-cyan-400">
                Panel Administrator
            </p>
        </div>

    </a>

    <button
        id="closeSidebarBtn"
        type="button"
        aria-label="Tutup menu"
        class="relative ml-3 flex h-9 w-9 shrink-0
               items-center justify-center rounded-xl
               border border-slate-200 dark:border-slate-700
               bg-slate-50 dark:bg-white/[0.04]
               text-slate-500 dark:text-slate-400
               transition-all
               hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700
               dark:hover:border-slate-600 dark:hover:bg-white/[0.08]
               dark:hover:text-white
               focus:outline-none focus:ring-2
               focus:ring-blue-400/40 dark:focus:ring-cyan-400/40">
        ✕
    </button>

</div>


    {{-- MOBILE NAVIGATION --}}
    <nav class="flex-1 overflow-y-auto px-3 py-5
                [scrollbar-color:#cbd5e1_transparent]
                dark:[scrollbar-color:#334155_transparent]
                [scrollbar-width:thin]">

        <p class="mb-3 px-3 text-[10px] font-bold uppercase
                  tracking-[0.18em] text-slate-500">
            Menu Utama
        </p>

        <div class="space-y-1.5">
            @foreach ($navItems as $item)
                @can($item['permission'])
                    <x-admin.nav-link
                        :route="$item['route']"
                        :icon="$item['icon']"
                        :variant="$item['route'] === 'admin.dokumentasi.index'
                            ? 'highlight'
                            : 'default'">
                        {{ $item['label'] }}
                    </x-admin.nav-link>
                @endcan
            @endforeach
        </div>

        {{-- MOBILE MANAJEMEN SISTEM --}}
        @canany(['kelola-dinas', 'kelola-admin'])
            <div class="mt-7 border-t border-slate-200 dark:border-slate-800 pt-5">

                <p class="mb-3 px-3 text-[10px] font-bold uppercase
                          tracking-[0.18em] text-slate-500">
                    Manajemen Sistem
                </p>

                <div class="space-y-1.5">
                    @can('kelola-dinas')
                        <x-admin.nav-link
                            route="admin.dinas.index"
                            icon="🏢">
                            Kelola Dinas
                        </x-admin.nav-link>
                    @endcan

                    @can('kelola-admin')
                        <x-admin.nav-link
                            route="admin.pengguna.index"
                            icon="👤">
                            Kelola Admin
                        </x-admin.nav-link>
                    @endcan
                </div>
            </div>
        @endcanany
    </nav>

    {{-- MOBILE LOGOUT --}}
    <div class="shrink-0 border-t border-slate-200 dark:border-slate-800 p-3">

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit"
                class="group flex w-full items-center rounded-xl
                       px-3 py-3 text-left text-sm font-semibold
                       text-slate-600 dark:text-slate-400
                       transition-all duration-200
                       hover:bg-rose-50 hover:text-rose-700
                       dark:hover:bg-rose-500/10 dark:hover:text-rose-300
                       focus:outline-none focus:ring-2
                       focus:ring-rose-400/40">
                <span>Keluar Sistem</span>
            </button>
        </form>

        <div class="mt-3 flex items-center gap-2 px-3 pb-1">
            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500
                         dark:bg-emerald-400 shadow-sm
                         shadow-emerald-400/50">
            </span>

            <p class="text-[10px] tracking-wide text-slate-500">
                SIMANTARA · Kabupaten Ponorogo
            </p>
        </div>
    </div>
</aside>
