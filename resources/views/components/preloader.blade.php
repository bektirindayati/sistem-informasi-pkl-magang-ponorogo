{{-- 
    Komponen: <x-preloader />
    Preloader animasi saat halaman dimuat.
--}}

<div
    id="preloader"
    class="fixed inset-0 z-[100] flex items-center justify-center
           bg-slate-50
           transition-opacity duration-700 ease-out
           overflow-hidden"
>

    {{-- SOFT BACKGROUND GLOW --}}
    <div
        class="absolute top-1/2 left-1/2
               -translate-x-1/2 -translate-y-1/2
               w-[420px] h-[420px] sm:w-[560px] sm:h-[560px]
               rounded-full
               bg-gradient-to-br from-blue-200/40 via-cyan-200/30 to-transparent
               blur-[100px]
               pointer-events-none"
    ></div>

    {{-- CONTENT --}}
    <div class="relative z-10 flex flex-col items-center text-center px-6">

        {{-- LOGO --}}
        <div class="relative flex items-center justify-center">

            {{-- OUTER SOFT RING --}}
            <div
                class="absolute
                       h-28 w-28 sm:h-32 sm:w-32
                       rounded-full
                       border border-blue-200/70
                       animate-ping"
                style="animation-duration: 2.2s;"
            ></div>

            {{-- SPINNER --}}
            <div
                class="absolute
                       h-28 w-28 sm:h-32 sm:w-32
                       rounded-full
                       border-[3px]
                       border-slate-200
                       border-t-blue-600
                       border-r-cyan-500
                       animate-spin"
                style="animation-duration: 1.5s;"
            ></div>

            {{-- LOGO CORE --}}
            <div
    class="relative
           h-20 w-20 sm:h-24 sm:w-24
           rounded-[26px]
           bg-white
           flex items-center justify-center
           overflow-hidden
           shadow-xl shadow-blue-500/20"
>
    <img
        src="{{ asset('images/logo-simantara.jpg') }}"
        alt="Logo SIMANTARA"
        class="w-full h-full object-contain"
    >
</div>

        </div>


        {{-- BRAND --}}
        <div class="mt-10">

            <h2
                class="text-3xl sm:text-4xl
                       font-extrabold
                       tracking-[0.08em]
                       text-slate-900"
            >
                SIMANTARA
            </h2>

            <p
                class="mt-2
                       text-xs sm:text-sm
                       font-semibold
                       tracking-wide
                       text-blue-600"
            >
                Sistem Informasi Magang Terintegrasi
            </p>

            <p
                class="mt-0.5
                       text-xs sm:text-sm
                       font-medium
                       text-slate-400"
            >
                Kabupaten Ponorogo
            </p>

        </div>


        {{-- LOADING STATUS --}}
        <div class="mt-8 flex items-center gap-2.5">

            <span class="relative flex h-2 w-2">

                <span
                    class="absolute inline-flex
                           h-full w-full
                           rounded-full
                           bg-blue-400
                           opacity-60
                           animate-ping"
                ></span>

                <span
                    class="relative inline-flex
                           h-2 w-2
                           rounded-full
                           bg-blue-600"
                ></span>

            </span>

            <span
                class="text-[11px] sm:text-xs
                       font-semibold
                       text-slate-500"
            >
                Memuat halaman...
            </span>

        </div>

    </div>

</div>


{{-- Script preloader --}}
@once
<script>
    window.addEventListener('load', () => {
        const preloader = document.getElementById('preloader');

        if (preloader) {
            setTimeout(() => {
                preloader.classList.add('opacity-0');

                setTimeout(() => {
                    preloader.style.display = 'none';
                }, 700);

            }, 900);
        }
    });
</script>
@endonce