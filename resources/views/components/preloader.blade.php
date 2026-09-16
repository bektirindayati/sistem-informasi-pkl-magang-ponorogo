{{--Komponen: <x-preloader />
    Preloader animasi saat halaman dimuat. Tidak menerima props —
    cukup dipanggil di awal <body> pada layout mana pun yang butuh preloader.--}}
<div id="preloader" class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-slate-50 transition-opacity duration-700 ease-out overflow-hidden">
    <div class="absolute w-[700px] h-[700px] bg-gradient-to-tr from-blue-200/50 via-cyan-200/40 to-sky-200/50 rounded-full blur-[120px] pointer-events-none animate-pulse"></div>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)] bg-[size:4rem_4rem] pointer-events-none opacity-60"></div>

    <div class="relative flex flex-col items-center z-10">
        <div class="relative flex items-center justify-center p-10">
            <div class="absolute inset-0 rounded-full border-4 border-slate-200/80 border-t-cyan-500 border-r-blue-600 animate-spin"></div>
            <div class="h-32 w-32 md:h-40 md:w-40 rounded-full bg-gradient-to-br from-blue-600 to-cyan-600 flex items-center justify-center text-white font-black text-6xl md:text-7xl shadow-xl relative z-10">
                M
            </div>
        </div>
        <div class="mt-10 flex flex-col items-center text-center px-4">
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-[0.1em] text-slate-900">MagangHub</h2>
            <p class="text-sm md:text-base text-blue-600 font-bold tracking-wide mt-2">Portal Pendaftaran Magang Terpadu</p>
            <div class="mt-8 flex items-center gap-2.5 px-5 py-2 rounded-full bg-white border border-slate-200 shadow-md">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600"></span>
                </span>
                <span class="text-xs font-bold text-slate-700">Memuat halaman...</span>
            </div>
        </div>
    </div>
</div>

{{-- Script preloader disatukan di sini supaya x-preloader benar-benar plug-and-play --}}
@once
<script>
    window.addEventListener('load', () => {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            setTimeout(() => {
                preloader.classList.add('opacity-0');
                setTimeout(() => { preloader.style.display = 'none'; }, 700);
            }, 1000);
        }
    });
</script>
@endonce
