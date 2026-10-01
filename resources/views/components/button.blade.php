{{--
    Tombol standar tanpa ikon panah: hierarki ditunjukkan lewat warna solid.

    <x-button :href="route('...')" variant="primary" size="md">Teks</x-button>   -> <a>
    <x-button type="submit" variant="danger" class="w-full">Teks</x-button>      -> <button>

    variant : primary | secondary | soft | success | warning | orange | danger | light
              (light = tombol putih untuk di atas latar biru/gelap)
    size    : sm | md
--}}
@props(['href' => null, 'variant' => 'primary', 'size' => 'md', 'type' => 'button'])

@php
    $varian = [
        'primary'   => 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700 hover:border-blue-700',
        'secondary' => 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50 hover:border-slate-400',
        'soft'      => 'bg-blue-50 text-blue-700 border-blue-100 hover:bg-blue-100',
        'success'   => 'bg-emerald-600 text-white border-emerald-600 hover:bg-emerald-700 hover:border-emerald-700',
        'warning'   => 'bg-amber-500 text-white border-amber-500 hover:bg-amber-600 hover:border-amber-600',
        'orange'    => 'bg-orange-500 text-white border-orange-500 hover:bg-orange-600 hover:border-orange-600',
        'danger'    => 'bg-white text-rose-600 border-rose-200 hover:bg-rose-50 hover:border-rose-300',
        'light'     => 'bg-white text-blue-700 border-white hover:bg-blue-50 hover:border-blue-50',
    ];

    $ukuran = [
        'sm' => 'px-3.5 py-2 text-xs',
        'md' => 'px-5 py-2.5 text-sm',
    ];

    $kelas = 'inline-flex items-center justify-center gap-2 rounded-lg border font-semibold transition '
           . 'focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 '
           . 'disabled:opacity-60 disabled:cursor-not-allowed '
           . ($varian[$variant] ?? $varian['primary']) . ' '
           . ($ukuran[$size] ?? $ukuran['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->class([$kelas]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->class([$kelas]) }}>{{ $slot }}</button>
@endif