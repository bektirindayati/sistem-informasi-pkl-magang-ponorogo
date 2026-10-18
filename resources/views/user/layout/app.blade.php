{{-- resources/views/user/layout/app.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Pengguna') - SiMagang</title>

    {{--
        Tema: pakai partial 'theme-script' SAJA (bukan 'tailwind-tema'),
        karena layout ini memuat Tailwind lewat Vite, bukan CDN.
        Harus sebelum @vite supaya halaman tidak berkedip terang.
    --}}
    @include('partials.theme-script')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

    @stack('styles')
</head>

<body class="min-h-screen bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased">

    {{-- Navbar user (pill mengambang) --}}
    <x-user-navbar />


    {{-- Main Content --}}
    <main class="min-h-screen pt-24 sm:pt-28">
        @yield('content')
    </main>


    {{-- Footer --}}
    <footer class="bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">

                <p class="text-xs text-slate-400 dark:text-slate-500 text-center sm:text-left">
                    © {{ date('Y') }} SiMagang Kabupaten Ponorogo
                </p>

                <p class="text-xs text-slate-400 dark:text-slate-500">
                    Portal Pendaftaran PKL & Magang
                </p>

            </div>

        </div>
    </footer>

    @stack('scripts')

</body>
</html>