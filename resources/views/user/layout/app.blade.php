{{-- resources/views/user/layout/app.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard Pengguna') - SiMagang</title>

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

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    {{--
        Navbar sekarang lewat komponen <x-user-navbar />, bukan ditulis
        inline di sini -- konsisten dengan <x-navbar /> yang dipakai di
        halaman publik (welcome.blade.php). Isi & gaya navbar (pill
        mengambang) ada di resources/views/components/user-navbar.blade.php.
    --}}
    <x-user-navbar />


    {{-- Main Content --}}
    {{-- pt-24 sm:pt-28 -> menyamai jarak yang dipakai di halaman Beranda
         publik untuk navbar mengambang serupa, supaya konten tidak
         ketiban navbar. --}}
    <main class="min-h-screen pt-24 sm:pt-28">
        @yield('content')
    </main>


    {{-- Footer --}}
    <footer class="bg-white border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">

                <p class="text-xs text-slate-400 text-center sm:text-left">
                    © {{ date('Y') }} SiMagang Kabupaten Ponorogo
                </p>

                <p class="text-xs text-slate-400">
                    Portal Pendaftaran PKL & Magang
                </p>

            </div>

        </div>
    </footer>

    @stack('scripts')

</body>
</html>