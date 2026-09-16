{{--
    Komponen: <x-step-heading number="1" variant="blue">Alur Pendaftaran</x-step-heading>
              <x-step-heading number="2" variant="indigo">Periode Pelaksanaan Magang</x-step-heading>

    variant "blue"   -> gaya di informasi.blade.php (lingkaran solid biru)
    variant "indigo" -> gaya di create.blade.php (lingkaran indigo soft)
--}}
@props(['number', 'variant' => 'indigo'])

@php
    $circleClass = $variant === 'blue'
        ? 'w-7 h-7 rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/20'
        : 'w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600';
@endphp

<h2 class="text-base font-extrabold text-slate-900 mb-5 flex items-center gap-2.5">
    <span class="{{ $circleClass }} flex items-center justify-center text-xs sm:text-sm font-bold shrink-0">{{ $number }}</span>
    {{ $slot }}
</h2>
