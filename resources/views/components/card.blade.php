{{--
    Kartu standar SiMANTARA.

    <x-card>Konten</x-card>
    <x-card :pad="false">Konten tanpa padding bawaan</x-card>
    <x-card class="lg:col-span-2">Konten</x-card>
--}}

@props(['pad' => true])

<div
    {{ $attributes->class([
        'bg-white dark:bg-slate-800',
        'border border-slate-200 dark:border-slate-700',
        'rounded-2xl shadow-sm dark:shadow-none',
        'overflow-hidden transition-colors duration-200',
        'p-5 sm:p-6' => $pad,
    ]) }}
>
    {{ $slot }}
</div>