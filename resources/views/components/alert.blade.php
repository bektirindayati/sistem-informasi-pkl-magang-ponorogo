{{-- resources/views/components/alert.blade.php --}}
@props(['type' => 'success'])

@php
    $warna = [
        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-800 dark:bg-emerald-500/10 dark:border-emerald-500/30 dark:text-emerald-200',
        'error'   => 'bg-rose-50 border-rose-200 text-rose-800 dark:bg-rose-500/10 dark:border-rose-500/30 dark:text-rose-200',
        'warning' => 'bg-amber-50 border-amber-200 text-amber-800 dark:bg-amber-500/10 dark:border-amber-500/30 dark:text-amber-200',
        'info'    => 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-500/10 dark:border-blue-500/30 dark:text-blue-200',
    ];
@endphp

<div role="alert" {{ $attributes->merge(['class' => 'rounded-2xl border px-4 py-3 text-sm font-semibold ' . ($warna[$type] ?? $warna['info'])]) }}>
    {{ $slot }}
</div>