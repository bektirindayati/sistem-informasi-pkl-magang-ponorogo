{{--
    Tombol standar SiMANTARA.

    <x-button :href="route('user.dashboard')" variant="primary">
        Kembali
    </x-button>

    <x-button type="submit" variant="primary">
        Simpan Perubahan
    </x-button>

    Variant:
    primary | secondary | soft | success | warning | orange | danger | light

    Size:
    sm | md
--}}

@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $varian = [
        'primary' =>
            'bg-blue-600 text-white border-blue-600
             hover:bg-blue-700 hover:border-blue-700
             dark:bg-blue-600 dark:border-blue-600
             dark:hover:bg-blue-500 dark:hover:border-blue-500',

        'secondary' =>
            'bg-white text-slate-700 border-slate-300
             hover:bg-slate-100 hover:border-slate-400
             dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600
             dark:hover:bg-slate-700 dark:hover:border-slate-500',

        'soft' =>
            'bg-blue-50 text-blue-700 border-blue-100
             hover:bg-blue-100
             dark:bg-blue-500/10 dark:text-blue-300 dark:border-blue-500/20
             dark:hover:bg-blue-500/20',

        'success' =>
            'bg-emerald-600 text-white border-emerald-600
             hover:bg-emerald-700 hover:border-emerald-700
             dark:bg-emerald-600 dark:border-emerald-600
             dark:hover:bg-emerald-500',

        'warning' =>
            'bg-amber-500 text-white border-amber-500
             hover:bg-amber-600 hover:border-amber-600
             dark:bg-amber-500 dark:border-amber-500
             dark:hover:bg-amber-400',

        'orange' =>
            'bg-orange-500 text-white border-orange-500
             hover:bg-orange-600 hover:border-orange-600
             dark:bg-orange-500 dark:border-orange-500
             dark:hover:bg-orange-400',

        'danger' =>
            'bg-white text-rose-600 border-rose-200
             hover:bg-rose-50 hover:border-rose-300
             dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/30
             dark:hover:bg-rose-500/20 dark:hover:border-rose-500/50',

        'light' =>
            'bg-white text-blue-700 border-slate-200
             hover:bg-blue-50 hover:border-blue-200
             dark:bg-slate-100 dark:text-slate-800 dark:border-slate-200
             dark:hover:bg-white',
    ];

    $ukuran = [
        'sm' => 'px-3.5 py-2 text-xs',
        'md' => 'px-5 py-2.5 text-sm',
    ];

    $kelas = 'inline-flex items-center justify-center gap-2 rounded-xl border font-semibold '
           . 'transition-colors duration-200 '
           . 'focus:outline-none focus-visible:ring-2 '
           . 'focus-visible:ring-blue-500 dark:focus-visible:ring-cyan-500 '
           . 'focus-visible:ring-offset-2 focus-visible:ring-offset-white '
           . 'dark:focus-visible:ring-offset-slate-800 '
           . 'disabled:opacity-60 disabled:cursor-not-allowed '
           . ($varian[$variant] ?? $varian['primary']) . ' '
           . ($ukuran[$size] ?? $ukuran['md']);
@endphp

@if ($href)
    <a
        href="{{ $href }}"
        {{ $attributes->class([$kelas]) }}
    >
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        {{ $attributes->class([$kelas]) }}
    >
        {{ $slot }}
    </button>
@endif