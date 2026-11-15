{{-- <x-sidebar /> : sidebar admin (desktop + drawer mobile). Menu mengikuti permission Spatie. --}}

{{-- DESKTOP --}}
<aside class="hidden md:flex h-screen w-64 shrink-0 flex-col
              bg-white dark:bg-[#0b1220] border-r border-slate-200 dark:border-slate-800
              text-slate-700 dark:text-slate-300 shadow-xl shadow-slate-200/40 dark:shadow-black/20">

    <div class="relative overflow-hidden border-b border-slate-200 dark:border-slate-800 px-5 py-5">
        <div class="pointer-events-none absolute -right-8 -top-10 h-28 w-28 rounded-full bg-blue-400/10 dark:bg-cyan-500/[0.08] blur-2xl"></div>
        <x-admin.sidebar-brand />
    </div>

    <x-admin.sidebar-nav />
</aside>

{{-- MOBILE: LATAR --}}
<div id="mobileSidebarBackdrop"
     class="fixed inset-0 z-40 hidden bg-slate-900/40 dark:bg-slate-950/70 opacity-0 backdrop-blur-sm
            transition-opacity duration-300 md:hidden"></div>

{{-- MOBILE: DRAWER --}}
<aside id="mobileSidebar"
       class="fixed inset-y-0 left-0 z-50 flex h-screen w-72 max-w-[88vw] -translate-x-full flex-col
              bg-white dark:bg-[#0b1220] border-r border-slate-200 dark:border-slate-800
              text-slate-700 dark:text-slate-300 shadow-2xl shadow-slate-900/10 dark:shadow-black/30
              transition-transform duration-300 ease-in-out md:hidden">

    <div class="relative flex shrink-0 items-center justify-between gap-3 overflow-hidden
                border-b border-slate-200 dark:border-slate-800 px-5 py-5">
        <div class="pointer-events-none absolute -right-8 -top-10 h-28 w-28 rounded-full bg-blue-400/10 dark:bg-cyan-500/[0.08] blur-2xl"></div>

        <x-admin.sidebar-brand />

        <button id="closeSidebarBtn" type="button" aria-label="Tutup menu"
                class="relative flex h-9 w-9 shrink-0 items-center justify-center rounded-lg
                       border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-white/[0.04]
                       text-slate-500 dark:text-slate-400 transition-colors
                       hover:bg-slate-100 hover:text-slate-800 dark:hover:bg-white/[0.08] dark:hover:text-white
                       focus:outline-none focus:ring-2 focus:ring-blue-400/40 dark:focus:ring-cyan-400/40">
            <x-ikon name="x" class="h-5 w-5" />
        </button>
    </div>

    <x-admin.sidebar-nav />
</aside>