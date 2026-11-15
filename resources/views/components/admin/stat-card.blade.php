{{-- <x-admin.stat-card icon="clock" color="amber" label="Menunggu Review" :value="3" catatan="..." :href="route('...')" /> --}}
@props(['icon' => 'document', 'color' => 'cyan', 'label' => '', 'value' => 0, 'catatan' => null, 'href' => null])

@php
    $warna = [
        'cyan'    => ['chip' => 'bg-cyan-50 border-cyan-100 text-cyan-600 dark:bg-cyan-500/10 dark:border-cyan-500/20 dark:text-cyan-300',             'bar' => 'bg-cyan-500'],
        'blue'    => ['chip' => 'bg-blue-50 border-blue-100 text-blue-600 dark:bg-blue-500/10 dark:border-blue-500/20 dark:text-blue-300',             'bar' => 'bg-blue-500'],
        'amber'   => ['chip' => 'bg-amber-50 border-amber-100 text-amber-600 dark:bg-amber-500/10 dark:border-amber-500/20 dark:text-amber-300',       'bar' => 'bg-amber-500'],
        'emerald' => ['chip' => 'bg-emerald-50 border-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-300', 'bar' => 'bg-emerald-500'],
        'rose'    => ['chip' => 'bg-rose-50 border-rose-100 text-rose-600 dark:bg-rose-500/10 dark:border-rose-500/20 dark:text-rose-300',             'bar' => 'bg-rose-500'],
    ];
    $w = $warna[$color] ?? $warna['cyan'];
@endphp

<div {{ $attributes->merge(['class' => 'group relative overflow-hidden bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-none p-5 flex items-center gap-4 transition hover:-translate-y-0.5 hover:shadow-md dark:hover:shadow-none']) }}>
    @if ($href)
        <a href="{{ $href }}" class="absolute inset-0 z-10" aria-label="{{ $label }}"></a>
    @endif

    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $w['bar'] }}"></div>

    <div class="w-12 h-12 shrink-0 rounded-2xl border flex items-center justify-center {{ $w['chip'] }}">
        <x-ikon :name="$icon" class="h-6 w-6" />
    </div>

    <div class="min-w-0">
        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ $label }}</p>
        <p class="text-3xl font-extrabold text-slate-900 dark:text-white leading-none mt-1.5">{{ $value }}</p>
        @if ($catatan)
            <p class="mt-1.5 text-xs text-slate-400 dark:text-slate-500">{{ $catatan }}</p>
        @endif
    </div>
</div>