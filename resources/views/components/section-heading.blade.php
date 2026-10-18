{{--
    resources/views/components/section-heading.blade.php
    Pemakaian:
        <x-section-heading eyebrow="Instansi Tujuan" title="Daftar Dinas & Instansi">
            Teks deskripsi singkat.
        </x-section-heading>
--}}
@props(['eyebrow' => null, 'title'])

<div class="text-center max-w-3xl mx-auto mb-8 sm:mb-12">

    @if ($eyebrow)
        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full
                     bg-blue-50 dark:bg-blue-500/15
                     border border-blue-100 dark:border-blue-400/20
                     text-[11px] sm:text-xs font-bold uppercase tracking-wider
                     text-blue-700 dark:text-blue-300">
            {{ $eyebrow }}
        </span>
    @endif

    <h2 class="mt-4 text-2xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">
        {{ $title }}
    </h2>

    @if (trim((string) $slot) !== '')
        <p class="mt-3 text-sm sm:text-base text-slate-500 dark:text-slate-400 leading-relaxed">
            {{ $slot }}
        </p>
    @endif

</div>