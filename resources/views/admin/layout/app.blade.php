<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('page-title', 'Dashboard Admin') - SIMAGANG Diskominfo</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Font --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /*
        |--------------------------------------------------------------------------
        | Admin Topbar
        |--------------------------------------------------------------------------
        | Tinggi topbar dibuat tetap agar konsisten di seluruh halaman admin.
        */
        .admin-topbar {
            height: 80px !important;
            min-height: 80px !important;
            max-height: 80px !important;
            flex-shrink: 0 !important;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-slate-100 text-slate-800 antialiased flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <x-admin.sidebar />


    {{-- AREA UTAMA --}}
    <div class="flex-1 flex flex-col min-w-0 min-h-0 overflow-hidden">

        {{-- TOPBAR--}}
        <x-admin.topbar />


        {{-- MAIN CONTENT --}}
        <main class="flex-1 min-h-0 min-w-0 overflow-y-auto">

            <div
                class="px-4 py-6 md:px-8 md:py-7
                       max-w-7xl mx-auto w-full
                       space-y-6 md:space-y-7"
            >
                @yield('content')
            </div>

        </main>

    </div>


    {{-- MOBILE SIDEBAR SCRIPT --}}
    <script>
        const openBtn = document.getElementById('openSidebarBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');
        const sidebar = document.getElementById('mobileSidebar');
        const backdrop = document.getElementById('mobileSidebarBackdrop');

        function toggleSidebar() {

            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }

            if (backdrop) {
                backdrop.classList.toggle('hidden');
            }
        }

        if (openBtn) {
            openBtn.addEventListener('click', toggleSidebar);
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', toggleSidebar);
        }

        if (backdrop) {
            backdrop.addEventListener('click', toggleSidebar);
        }
    </script>


    @stack('scripts')

</body>

</html>