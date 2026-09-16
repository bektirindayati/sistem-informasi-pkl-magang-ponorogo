{{--Komponen: <x-flowing-bg> ... section-section di sini ... </x-flowing-bg>--}}
@props(['class' => ''])

<div {{ $attributes->merge(['class' => 'relative overflow-hidden bg-[linear-gradient(to_bottom,#eff6ff_0%,#eff6ff_70%,#ffffff_100%)] ' . $class]) }}>

    {{-- Blob atas: menyambung visual dari blob hero di atasnya --}}
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[750px] h-[750px] bg-gradient-to-tr from-blue-200/40 via-cyan-100/30 to-transparent rounded-full blur-[140px] pointer-events-none"></div>

    {{-- Blob tengah: condong kanan, mengisi area Instansi --}}
    <div class="absolute top-[55%] -right-40 w-[550px] h-[550px] bg-gradient-to-bl from-cyan-200/25 via-blue-100/20 to-transparent rounded-full blur-[130px] pointer-events-none"></div>

    {{-- Blob bawah: condong kiri, memudar jadi putih menjelang footer --}}
    <div class="absolute bottom-0 -left-40 w-[600px] h-[600px] bg-gradient-to-tr from-blue-100/25 to-transparent rounded-full blur-[130px] pointer-events-none"></div>

    <div class="relative z-10">
        {{ $slot }}
    </div>

</div>