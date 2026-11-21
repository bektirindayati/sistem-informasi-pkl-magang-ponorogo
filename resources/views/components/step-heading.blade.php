{{-- resources/views/components/step-heading.blade.php --}}
{{--
    Komponen: <x-step-heading number="1" variant="blue">Alur Pendaftaran</x-step-heading>
              <x-step-heading number="2" variant="indigo">Periode Pelaksanaan Magang</x-step-heading>

    Deskripsi opsional (sejajar dengan teks judul):
              <x-step-heading number="1">
                  Instansi Tujuan
                  <x-slot name="desc">Tentukan instansi dan bidang penempatan.</x-slot>
              </x-step-heading>

    variant "blue"   -> lingkaran solid biru
    variant "indigo" -> lingkaran soft biru
--}}
@props(['number', 'variant' => 'indigo'])

@php
    $circleClass = $variant === 'blue'
        ? 'w-7 h-7 rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/20 dark:shadow-none'
        : 'w-8 h-8 rounded-lg bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-cyan-300';

    // Lebar lingkaran + jarak (gap-2.5) supaya deskripsi sejajar dengan teks judul.
    $indentDesc = $variant === 'blue' ? 'sm:ml-[2.375rem]' : 'sm:ml-[2.625rem]';
@endphp

<div class="mb-5">
    <h2 class="text-lg font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
        <span class="{{ $circleClass }} flex items-center justify-center text-xs sm:text-sm font-bold shrink-0">
            {{ $number }}
        </span>
        {{ $slot }}
    </h2>

    @if (isset($desc) && $desc->isNotEmpty())
        <p class="mt-2 {{ $indentDesc }} text-xs leading-relaxed text-slate-500 dark:text-slate-400">
            {{ $desc }}
        </p>
    @endif
</div>