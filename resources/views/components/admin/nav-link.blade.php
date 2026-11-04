
{{--
    Komponen: <x-admin.nav-link route="admin.dashboard" icon="📊">
                  Dashboard
              </x-admin.nav-link>

    Otomatis aktif sesuai halaman yang sedang dibuka.
    Mendukung light mode dan dark mode.
--}}

@props(['route', 'icon', 'variant' => null])

@php
    $active = request()->routeIs($route);

    if ($active) {
        $classes = '
            bg-blue-600 text-white font-bold
            shadow-md shadow-blue-500/20
            dark:bg-cyan-600 dark:text-white
            dark:shadow-cyan-900/30
        ';
    } elseif ($variant === 'highlight') {
        $classes = '
            bg-blue-50 text-blue-700 font-semibold
            hover:bg-blue-100 hover:text-blue-800
            dark:bg-cyan-500/10 dark:text-cyan-300
            dark:hover:bg-cyan-500/20 dark:hover:text-cyan-200
        ';
    } else {
        $classes = '
            text-slate-600 font-semibold
            hover:bg-blue-50 hover:text-blue-700
            dark:text-slate-300
            dark:hover:bg-slate-800 dark:hover:text-white
        ';
    }
@endphp

<a
    href="{{ route($route) }}"
    @if($active) aria-current="page" @endif
    class="flex items-center gap-3 px-4 py-3 rounded-xl
           transition-all duration-200 text-sm
           {{ $classes }}"
>
    <span class="w-5 shrink-0 text-center text-base">
        {{ $icon }}
    </span>

    <span class="min-w-0">
        {{ $slot }}
    </span>
</a>
