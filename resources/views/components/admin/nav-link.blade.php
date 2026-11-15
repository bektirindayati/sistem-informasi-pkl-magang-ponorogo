
{{-- <x-admin.nav-link route="admin.pendaftar.index" icon="users">Data Pendaftar</x-admin.nav-link> --}}

@props(['route', 'icon', 'exact' => false])

@php
    // Menu index dapat aktif di halaman turunannya,
    // kecuali jika exact bernilai true.
    $pola = str_ends_with($route, '.index')
        ? \Illuminate\Support\Str::beforeLast($route, '.index') . '.*'
        : $route;

    $active = $exact
        ? request()->routeIs($route)
        : request()->routeIs($pola);
@endphp

<a href="{{ route($route) }}"
   @if ($active) aria-current="page" @endif
   class="group relative flex items-center gap-3 rounded-lg px-3 py-2.5
          text-sm font-semibold transition-colors duration-150
          {{ $active
              ? 'bg-blue-50 text-blue-700 dark:bg-cyan-500/10 dark:text-cyan-300'
              : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-white dark:hover:bg-slate-800 dark:hover:text-white' }}">

    {{-- INDIKATOR MENU AKTIF --}}
    <span class="absolute left-0 top-1/2 h-5 w-1 -translate-y-1/2
                 rounded-r-full bg-blue-600 dark:bg-cyan-400
                 transition-opacity
                 {{ $active ? 'opacity-100' : 'opacity-0' }}">
    </span>

    {{-- IKON MENU --}}
    <x-ikon
        :name="$icon"
        class="h-5 w-5 shrink-0
               {{ $active
                   ? 'text-blue-600 dark:text-cyan-400'
                   : 'text-slate-400 group-hover:text-slate-600 dark:text-white dark:group-hover:text-white' }}" />

    {{-- NAMA MENU --}}
    <span class="min-w-0 truncate">{{ $slot }}</span>

</a>
