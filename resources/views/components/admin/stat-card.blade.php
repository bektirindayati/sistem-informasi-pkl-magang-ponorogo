{{-- Komponen: <x-admin.stat-card icon="📄" color="blue" label="Total Mahasiswa" :value="$totalMagang ?? 0" /> --}}
@props(['icon', 'label', 'value', 'color' => 'blue'])

@php
    $colors = [
        'blue'    => 'bg-blue-50 text-blue-600',
        'cyan'    => 'bg-cyan-50 text-cyan-700',
        'amber'   => 'bg-amber-50 text-amber-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
    ];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white p-5 md:p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4']) }}>
    <div class="w-12 h-12 rounded-2xl {{ $colors[$color] ?? $colors['blue'] }} flex items-center justify-center font-bold text-xl shrink-0">
        {{ $icon }}
    </div>
    <div>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $label }}</p>
        <h3 class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $value }}</h3>
    </div>
</div>