
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sesi Berakhir - SIMANTARA</title>

    @include('partials.theme-script')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased
             transition-colors duration-300
             dark:bg-slate-950 dark:text-slate-100">

    <main class="relative flex min-h-screen items-center justify-center
                 overflow-hidden px-5 py-12">

        {{-- Background decoration --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full
                        bg-blue-400/20 blur-3xl dark:bg-blue-500/10"></div>

            <div class="absolute -bottom-40 -right-32 h-[500px] w-[500px]
                        rounded-full bg-indigo-400/20 blur-3xl
                        dark:bg-indigo-500/10"></div>

            <div class="absolute left-1/2 top-0 h-full w-px
                        bg-gradient-to-b from-transparent
                        via-slate-200/70 to-transparent
                        dark:via-slate-800/70"></div>
        </div>

        {{-- Main content --}}
        <div class="relative z-10 w-full max-w-lg">

            {{-- Brand --}}
            <div class="mb-8 text-center">
                <div class="mb-3 inline-flex items-center gap-2.5">
                </div>

                <p class="text-xs font-medium tracking-[0.2em]
                          text-slate-400 dark:text-slate-500">
                    SISTEM INFORMASI MAGANG TERINTEGRASI
                </p>
            </div>

            {{-- Error card --}}
            <section class="rounded-[2rem] border border-white/80
                            bg-white/80 p-7 shadow-2xl shadow-slate-300/30
                            backdrop-blur-xl sm:p-10
                            dark:border-slate-800
                            dark:bg-slate-900/80
                            dark:shadow-black/20">

                {{-- Icon --}}
                <div class="mb-7 flex justify-center">
                    <div class="relative flex h-24 w-24 items-center
                                justify-center rounded-[2rem]
                                bg-amber-50 dark:bg-amber-500/10">

                        <div class="absolute inset-0 rounded-[2rem]
                                    border border-amber-200/70
                                    dark:border-amber-500/20"></div>

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-12 w-12 text-amber-500"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.6">
                            <circle cx="12" cy="12" r="9"/>
                            <path stroke-linecap="round"
                                  d="M12 7v5"/>
                            <path stroke-linecap="round"
                                  stroke-width="2.5"
                                  d="M12 16h.01"/>
                        </svg>

                        <span class="absolute -right-1 -top-1 flex
                                     h-6 w-6 items-center justify-center
                                     rounded-full border-4 border-white
                                     bg-amber-400 dark:border-slate-900">
                            <span class="h-1.5 w-1.5 rounded-full
                                         bg-white"></span>
                        </span>
                    </div>
                </div>

                {{-- Status --}}
                <div class="mb-4 flex justify-center">
                    <span class="rounded-full border border-amber-200
                                 bg-amber-50 px-3 py-1 text-[11px]
                                 font-bold uppercase tracking-wider
                                 text-amber-600
                                 dark:border-amber-500/20
                                 dark:bg-amber-500/10
                                 dark:text-amber-400">
                        Sesi Tidak Aktif
                    </span>
                </div>

                {{-- Heading --}}
                <div class="text-center">
                    <h1 class="text-2xl font-extrabold tracking-tight
                               text-slate-900 sm:text-3xl
                               dark:text-white">
                        Sesi Anda Telah Berakhir
                    </h1>

                    <p class="mx-auto mt-4 max-w-sm text-sm leading-7
                              text-slate-500 dark:text-slate-400">
                        Sepertinya sesi Anda sudah tidak aktif karena
                        terlalu lama tidak digunakan. Silakan masuk
                        kembali untuk melanjutkan aktivitas di SIMANTARA.
                    </p>
                </div>

                {{-- Information --}}
                <div class="mt-7 flex gap-3 rounded-2xl border
                            border-blue-100 bg-blue-50/70 p-4
                            dark:border-blue-500/20
                            dark:bg-blue-500/5">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="mt-0.5 h-5 w-5 shrink-0 text-blue-500"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round"
                              d="M12 11v5m0-8h.01"/>
                    </svg>

                    <p class="text-xs leading-6 text-blue-700
                              dark:text-blue-300">
                        Untuk menjaga keamanan akun, silakan login
                        kembali. Jika sebelumnya Anda sedang mengisi
                        formulir, periksa kembali data sebelum
                        mengirimkannya.
                    </p>
                </div>

                {{-- Buttons --}}
                <div class="mt-8 space-y-3">
                    <a href="{{ route('login') }}"
                       class="group flex w-full items-center justify-center
                              gap-2 rounded-xl bg-gradient-to-r
                              from-blue-600 to-indigo-600 px-5 py-3.5
                              text-sm font-bold text-white
                              shadow-lg shadow-blue-500/20
                              transition duration-200
                              hover:-translate-y-0.5
                              hover:shadow-xl hover:shadow-blue-500/25
                              active:translate-y-0">

                        Masuk Kembali

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4 transition-transform
                                    group-hover:translate-x-1"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M5 12h14m-6-6 6 6-6 6"/>
                        </svg>
                    </a>

                    <a href="{{ url('/') }}"
                       class="flex w-full items-center justify-center
                              gap-2 rounded-xl border border-slate-200
                              bg-white px-5 py-3.5 text-sm font-semibold
                              text-slate-600 transition duration-200
                              hover:border-slate-300 hover:bg-slate-50
                              dark:border-slate-700 dark:bg-slate-800
                              dark:text-slate-300
                              dark:hover:bg-slate-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4"
                             viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-6v-7h-4v7H5a1 1 0 0 1-1-1z"/>
                        </svg>

                        Kembali ke Beranda
                    </a>
                </div>
            </section>

            {{-- Footer --}}
            <footer class="mt-7 text-center">
                <p class="text-xs font-medium text-slate-400
                          dark:text-slate-600">
                    &copy; {{ date('Y') }} SIMANTARA
                    <span class="mx-1">·</span>
                    Kabupaten Ponorogo
                </p>

                <p class="mt-2 text-[10px] tracking-wide
                          text-slate-400/80 dark:text-slate-600">
                    SISTEM INFORMASI MAGANG TERINTEGRASI
                </p>
            </footer>

        </div>
    </main>

</body>
</html>
