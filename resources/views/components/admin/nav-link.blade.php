{{--
    Komponen: <x-admin.nav-link route="admin.dashboard" icon="📊">Dashboard</x-admin.nav-link>
    Otomatis aktif sesuai halaman yang sedang dibuka (request()->routeIs()).

    Warna disamakan dengan skema baru sidebar: aksen cyan solid,
    bukan gradasi biru-indigo yang kelihatan keunguan.
--}}
@props(['route', 'icon', 'variant' => null])

@php
    $active = request()->routeIs($route);
    $classes = $active
        ? 'bg-cyan-600 text-white font-bold shadow-md shadow-cyan-900/30'
        : ($variant === 'highlight'
            ? 'hover:bg-slate-800 text-amber-400 font-bold'
            : 'hover:bg-slate-800 hover:text-white text-slate-300 font-semibold');
@endphp

<a href="{{ route($route) }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-sm {{ $classes }}">
    <span>{{ $icon }}</span> {{ $slot }}
</a>