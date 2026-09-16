{{-- 
    Komponen: <x-sidebar />
    Sidebar admin (desktop + drawer mobile)

    Menu sidebar ditampilkan berdasarkan permission Spatie.
    Setiap menu menggunakan @can() agar UI otomatis mengikuti
    permission yang dimiliki user.
--}}


{{-- =========================================================
    SIDEBAR DESKTOP
    ========================================================= --}}
<aside
    class="w-64 bg-slate-950 text-slate-300
           flex flex-col justify-between
           border-r border-slate-800
           hidden md:flex shrink-0"
>

    <div>

        {{-- Logo --}}
        <div
            class="h-20 flex items-center px-6 gap-3
                   border-b border-slate-800"
        >
            <div
                class="h-9 w-9 rounded-xl bg-cyan-600
                       flex items-center justify-center
                       text-white font-black text-lg
                       shadow-lg shadow-cyan-900/40"
            >
                S
            </div>

            <span
                class="font-extrabold text-white
                       tracking-wider text-lg"
            >
                MAGANGHub
            </span>
        </div>


        {{-- Menu --}}
        <nav class="p-4 space-y-1.5">

            {{-- Dashboard --}}
            @can('lihat-dashboard')
                <x-admin.nav-link
                    route="admin.dashboard"
                    icon="📊"
                >
                    Dashboard
                </x-admin.nav-link>
            @endcan


            {{-- Data Pendaftar --}}
            @can('lihat-pendaftar')
                <x-admin.nav-link
                    route="admin.pendaftar.index"
                    icon="📁"
                >
                    Data Pendaftar
                </x-admin.nav-link>
            @endcan


            {{-- Verifikasi Berkas --}}
            @can('verifikasi-pendaftar')
                <x-admin.nav-link
                    route="admin.verifikasi.index"
                    icon="✅"
                >
                    Verifikasi Berkas
                </x-admin.nav-link>
            @endcan


            {{-- Kelola Instansi/Bidang --}}
            @can('kelola-instansi')
                <x-admin.nav-link
                    route="admin.instansi.index"
                    icon="🏛️"
                >
                    Kelola Instansi/Bidang
                </x-admin.nav-link>
            @endcan


            {{-- Kelola Dokumentasi --}}
            @can('kelola-dokumentasi')
                <x-admin.nav-link
                    route="admin.dokumentasi.index"
                    icon="📸"
                    variant="highlight"
                >
                    Kelola Dokumentasi
                </x-admin.nav-link>
            @endcan


            {{-- =================================================
                MANAJEMEN SISTEM
                ================================================= --}}
            @canany(['kelola-dinas', 'kelola-admin'])

                <div
                    class="pt-3 mt-3
                           border-t border-slate-800"
                >
                    <p
                        class="px-4 pb-2
                               text-[10px] font-bold
                               text-slate-500
                               uppercase tracking-wider"
                    >
                        Manajemen Sistem
                    </p>


                    {{-- Kelola Dinas --}}
                    @can('kelola-dinas')
                        <x-admin.nav-link
                            route="admin.dinas.index"
                            icon="🏢"
                        >
                            Kelola Dinas
                        </x-admin.nav-link>
                    @endcan


                    {{-- Kelola Admin --}}
                    @can('kelola-admin')
                        <x-admin.nav-link
                            route="admin.pengguna.index"
                            icon="👤"
                        >
                            Kelola Admin
                        </x-admin.nav-link>
                    @endcan

                </div>

            @endcanany

        </nav>

    </div>


    {{-- Logout --}}
    <div
        class="p-4 border-t border-slate-800"
    >
        <form
            action="{{ route('logout') }}"
            method="POST"
        >
            @csrf

            <button
                type="submit"
                class="w-full flex items-center gap-3
                       px-4 py-3 rounded-xl
                       text-rose-400
                       hover:bg-rose-500/10
                       transition
                       font-bold text-sm"
            >
                <span>🚪</span>
                Keluar Sistem
            </button>
        </form>
    </div>

</aside>



{{-- =========================================================
    SIDEBAR MOBILE / DRAWER
    ========================================================= --}}

{{-- Backdrop --}}
<div
    id="mobileSidebarBackdrop"
    class="fixed inset-0
           bg-slate-950/70
           z-40 hidden md:hidden
           backdrop-blur-sm
           transition-opacity"
></div>


{{-- Drawer --}}
<aside
    id="mobileSidebar"
    class="fixed inset-y-0 left-0
           w-72
           bg-slate-950
           text-slate-300
           flex flex-col justify-between
           border-r border-slate-800
           z-50
           transform -translate-x-full
           transition-transform
           duration-300
           ease-in-out
           md:hidden
           shadow-2xl"
>

    <div>

        {{-- Header Drawer --}}
        <div
            class="h-20 flex items-center
                   justify-between px-6
                   border-b border-slate-800"
        >

            <div class="flex items-center gap-3">

                <div
                    class="h-9 w-9 rounded-xl
                           bg-cyan-600
                           flex items-center justify-center
                           text-white
                           font-black text-lg
                           shadow-lg shadow-cyan-900/40"
                >
                    S
                </div>

                <span
                    class="font-extrabold text-white
                           tracking-wider text-lg"
                >
                    MAGANGHub
                </span>

            </div>


            <button
                id="closeSidebarBtn"
                type="button"
                class="text-slate-400
                       hover:text-white
                       p-1 rounded-lg
                       hover:bg-slate-800
                       transition"
            >
                ✕
            </button>

        </div>


        {{-- Menu Mobile --}}
        <nav class="p-4 space-y-1.5">

            {{-- Dashboard --}}
            @can('lihat-dashboard')
                <x-admin.nav-link
                    route="admin.dashboard"
                    icon="📊"
                >
                    Dashboard
                </x-admin.nav-link>
            @endcan


            {{-- Data Pendaftar --}}
            @can('lihat-pendaftar')
                <x-admin.nav-link
                    route="admin.pendaftar.index"
                    icon="📁"
                >
                    Data Pendaftar
                </x-admin.nav-link>
            @endcan


            {{-- Verifikasi Berkas --}}
            @can('verifikasi-pendaftar')
                <x-admin.nav-link
                    route="admin.verifikasi.index"
                    icon="✅"
                >
                    Verifikasi Berkas
                </x-admin.nav-link>
            @endcan


            {{-- Kelola Instansi/Bidang --}}
            @can('kelola-instansi')
                <x-admin.nav-link
                    route="admin.instansi.index"
                    icon="🏛️"
                >
                    Kelola Instansi/Bidang
                </x-admin.nav-link>
            @endcan


            {{-- Kelola Dokumentasi --}}
            @can('kelola-dokumentasi')
                <x-admin.nav-link
                    route="admin.dokumentasi.index"
                    icon="📸"
                    variant="highlight"
                >
                    Kelola Dokumentasi
                </x-admin.nav-link>
            @endcan


            {{-- =================================================
                MANAJEMEN SISTEM
                ================================================= --}}
            @canany(['kelola-dinas', 'kelola-admin'])

                <div
                    class="pt-3 mt-3
                           border-t border-slate-800"
                >
                    <p
                        class="px-4 pb-2
                               text-[10px] font-bold
                               text-slate-500
                               uppercase tracking-wider"
                    >
                        Manajemen Sistem
                    </p>


                    {{-- Kelola Dinas --}}
                    @can('kelola-dinas')
                        <x-admin.nav-link
                            route="admin.dinas.index"
                            icon="🏢"
                        >
                            Kelola Dinas
                        </x-admin.nav-link>
                    @endcan


                    {{-- Kelola Admin --}}
                    @can('kelola-admin')
                        <x-admin.nav-link
                            route="admin.pengguna.index"
                            icon="👤"
                        >
                            Kelola Admin
                        </x-admin.nav-link>
                    @endcan

                </div>

            @endcanany

        </nav>

    </div>


    {{-- Logout Mobile --}}
    <div
        class="p-4 border-t border-slate-800"
    >
        <form
            action="{{ route('logout') }}"
            method="POST"
        >
            @csrf

            <button
                type="submit"
                class="w-full flex items-center gap-3
                       px-4 py-3 rounded-xl
                       text-rose-400
                       hover:bg-rose-500/10
                       transition
                       font-bold text-sm"
            >
                <span>🚪</span>
                Keluar Sistem
            </button>
        </form>
    </div>

</aside>