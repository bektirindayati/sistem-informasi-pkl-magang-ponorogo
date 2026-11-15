
@php
    $menu = [
        ['lihat-dashboard', 'admin.dashboard', 'dashboard', 'Dashboard'],
        ['lihat-pendaftar', 'admin.pendaftar.index', 'users', 'Data Pendaftar'],
        ['verifikasi-pendaftar', 'admin.verifikasi.index', 'clipboard-check', 'Verifikasi Berkas'],
        ['kelola-instansi', 'admin.instansi.index', 'building', 'Kelola Instansi/Bidang'],
        ['kelola-dokumentasi', 'admin.dokumentasi.index', 'photo', 'Kelola Dokumentasi'],
    ];

    $sistem = [
        ['kelola-dinas', 'admin.dinas.index', 'office', 'Kelola Dinas'],
        ['kelola-admin', 'admin.pengguna.index', 'shield', 'Kelola Admin', true],
        ['kelola-admin', 'admin.pengguna.user', 'users', 'Kelola Pengguna'],
    ];

    $judul = 'mb-2 px-3 text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400 dark:text-slate-500';
@endphp

<nav class="flex-1 overflow-y-auto px-3 py-5
            [scrollbar-color:#cbd5e1_transparent]
            dark:[scrollbar-color:#334155_transparent]
            [scrollbar-width:thin]">

    {{-- MENU UTAMA --}}
    <p class="{{ $judul }}">Menu Utama</p>

    <div class="space-y-1">
        @foreach ($menu as [$izin, $rute, $ikon, $nama])
            @can($izin)
                <x-admin.nav-link :route="$rute" :icon="$ikon">
                    {{ $nama }}
                </x-admin.nav-link>
            @endcan
        @endforeach
    </div>

    {{-- MANAJEMEN SISTEM --}}
    @canany(['kelola-dinas', 'kelola-admin'])
        <div class="mt-6 border-t border-slate-200 dark:border-slate-800 pt-5">

            <p class="{{ $judul }}">Manajemen Sistem</p>

            <div class="space-y-1">
                @foreach ($sistem as $item)
                    @can($item[0])
                        <x-admin.nav-link
                            :route="$item[1]"
                            :icon="$item[2]"
                            :exact="$item[4] ?? false">
                            {{ $item[3] }}
                        </x-admin.nav-link>
                    @endcan
                @endforeach
            </div>

        </div>
    @endcanany

</nav>

{{-- BAGIAN BAWAH SIDEBAR --}}
<div class="shrink-0 border-t border-slate-200 dark:border-slate-800 p-3">

    <form action="{{ route('logout') }}" method="POST">
        @csrf

        <button
            type="submit"
            class="group flex w-full items-center gap-3 rounded-lg px-3 py-2.5
                   text-left text-sm font-semibold
                   text-slate-600 dark:text-slate-400
                   transition-colors duration-150
                   hover:bg-rose-50 hover:text-rose-700
                   dark:hover:bg-rose-500/10 dark:hover:text-rose-300
                   focus:outline-none focus:ring-2 focus:ring-rose-400/40">

            <x-ikon
                name="logout"
                class="h-5 w-5 text-slate-400 group-hover:text-rose-500 dark:text-slate-500" />

            Keluar Sistem
        </button>
    </form>

    <div class="mt-3 flex items-center gap-2 px-3 pb-1">
        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span>

        <p class="text-[10px] tracking-wide text-slate-500">
            SIMANTARA · Kabupaten Ponorogo
        </p>
    </div>

</div>
