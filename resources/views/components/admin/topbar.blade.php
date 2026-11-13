{{--
    Komponen: <x-topbar />
    Topbar admin (admin dinas & super admin) + tombol mode gelap/terang
--}}

<header
    class="admin-topbar
           bg-white dark:bg-slate-900
           border-b border-slate-200 dark:border-slate-700
           px-4 md:px-8
           flex items-center justify-between
           sticky top-0 z-30
           shadow-sm
           gap-2"
>

    {{-- =========================================================
        BAGIAN KIRI
        Tombol menu mobile + judul halaman
        ========================================================= --}}
    <div class="flex items-center gap-3 min-w-0 flex-1">

        {{-- Tombol buka sidebar mobile --}}
        <button
            id="openSidebarBtn"
            type="button"
            class="md:hidden p-2 rounded-xl
                   bg-slate-100 hover:bg-slate-200 text-slate-700
                   dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200
                   transition flex items-center justify-center shrink-0"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        {{-- Judul halaman --}}
        <h1 class="text-base md:text-xl font-extrabold text-slate-900 dark:text-white truncate min-w-0">
            @yield('page-title', 'Dashboard Admin')
        </h1>

    </div>


    {{-- =========================================================
        BAGIAN KANAN
        Tombol tema + informasi admin + avatar
        ========================================================= --}}
    <div class="flex items-center gap-2.5 shrink-0">

        {{-- Tombol mode gelap / terang --}}
        <x-theme-toggle />

        {{-- Informasi admin --}}
        <div class="text-right hidden sm:block max-w-[140px] md:max-w-[220px]">

            <p class="text-sm font-bold text-slate-900 dark:text-white leading-tight truncate">
                {{ Auth::user()->name ?? 'Admin' }}
            </p>

            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider truncate">
                {{ Auth::user()->isSuperAdmin()
                    ? 'Super Admin'
                    : (Auth::user()->dinas->nama_dinas ?? 'Belum ditugaskan ke dinas') }}
            </p>

        </div>

        
{{-- Avatar Admin --}}
@php
    $admin = Auth::user();

    $avatarUrl = $admin?->avatar
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($admin->avatar)
        : null;

    $inisial = collect(explode(' ', trim($admin?->name ?? 'AD')))
        ->filter()
        ->take(2)
        ->map(fn ($kata) => strtoupper(substr($kata, 0, 1)))
        ->implode('');
@endphp

<div
    class="w-9 h-9 md:w-10 md:h-10 rounded-full
           overflow-hidden shrink-0
           bg-cyan-50 text-cyan-700
           dark:bg-cyan-900/40 dark:text-cyan-300
           flex items-center justify-center
           font-extrabold text-xs md:text-sm
           border border-slate-200 dark:border-slate-700"
>
    @if ($avatarUrl)
        <img
            src="{{ $avatarUrl }}"
            alt="Foto profil {{ $admin->name }}"
            class="w-full h-full object-cover"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
        >
    @endif

    <span
        class="{{ $avatarUrl ? 'hidden' : 'flex' }}
               w-full h-full items-center justify-center"
    >
        {{ $inisial ?: 'AD' }}
    </span>
</div>
</header>