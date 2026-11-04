{{-- resources/views/components/admin/stat-card.blade.php --}}
@props(['icon' => '📄', 'color' => 'cyan', 'label' => '', 'value' => 0])

@php
    $warna = [
        'cyan'    => ['chip' => 'bg-cyan-50 border-cyan-100 dark:bg-cyan-500/10 dark:border-cyan-500/20',          'bar' => 'bg-cyan-500'],
        'blue'    => ['chip' => 'bg-blue-50 border-blue-100 dark:bg-blue-500/10 dark:border-blue-500/20',          'bar' => 'bg-blue-500'],
        'amber'   => ['chip' => 'bg-amber-50 border-amber-100 dark:bg-amber-500/10 dark:border-amber-500/20',      'bar' => 'bg-amber-500'],
        'emerald' => ['chip' => 'bg-emerald-50 border-emerald-100 dark:bg-emerald-500/10 dark:border-emerald-500/20', 'bar' => 'bg-emerald-500'],
        'rose'    => ['chip' => 'bg-rose-50 border-rose-100 dark:bg-rose-500/10 dark:border-rose-500/20',          'bar' => 'bg-rose-500'],
    ];
    $w = $warna[$color] ?? $warna['cyan'];
@endphp

<div {{ $attributes->merge(['class' => 'relative overflow-hidden bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-none p-5 md:p-6 flex items-center gap-4']) }}>
    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $w['bar'] }}"></div>

    <div class="w-12 h-12 shrink-0 rounded-2xl border flex items-center justify-center text-xl {{ $w['chip'] }}">
        {{ $icon }}
    </div>

    <div class="min-w-0">
        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $label }}</p>
        <p class="text-2xl md:text-3xl font-extrabold text-slate-900 dark:text-white leading-none mt-1.5">{{ $value }}</p>
    </div>
</div>