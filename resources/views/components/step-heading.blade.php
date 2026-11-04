
{{--
    Komponen: <x-step-heading number="1" variant="blue">Alur Pendaftaran</x-step-heading>
              <x-step-heading number="2" variant="indigo">Periode Pelaksanaan Magang</x-step-heading>

    variant "blue"   -> lingkaran solid biru
    variant "indigo" -> lingkaran soft biru
--}}
@props(['number', 'variant' => 'indigo'])

@php
    $circleClass = $variant === 'blue'
        ? 'w-7 h-7 rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/20 dark:shadow-none'
        : 'w-8 h-8 rounded-lg bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-cyan-300';
@endphp

<h2 class="text-base font-extrabold text-slate-900 dark:text-white mb-5 flex items-center gap-2.5">
    <span class="{{ $circleClass }} flex items-center justify-center text-xs sm:text-sm font-bold shrink-0">
        {{ $number }}
    </span>
    {{ $slot }}
</h2>
