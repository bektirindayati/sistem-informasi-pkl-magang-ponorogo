{{-- <x-status-badge :status="$item->status" /> --}}
{{-- Satu sumber warna & label status pendaftaran. --}}

@props(['status'])

@php
    $peta = [
        'draft' => [
            'Draft',
            'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-700/40 dark:text-slate-300 dark:border-slate-600'
        ],
        'pending' => [
            'Menunggu Verifikasi',
            'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-cyan-300 dark:border-blue-500/30'
        ],
        'revisi' => [
            'Perlu Revisi',
            'bg-orange-50 text-orange-700 border-orange-200 dark:bg-orange-500/10 dark:text-orange-300 dark:border-orange-500/30'
        ],
        'diterima' => [
            'Diterima',
            'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-500/30'
        ],
        'ditolak' => [
            'Ditolak',
            'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:border-rose-500/30'
        ],
        'selesai' => [
            'Selesai',
            'bg-cyan-50 text-cyan-800 border-cyan-200 dark:bg-cyan-500/10 dark:text-cyan-300 dark:border-cyan-500/30'
        ],
    ];

    [$label, $warna] = $peta[$status] ?? [
        ucfirst($status),
        'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-700/40 dark:text-slate-300 dark:border-slate-600'
    ];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center px-2.5 py-0.5 rounded-full border text-[11px] font-semibold',
    $warna,
]) }}>
    {{ $label }}
</span>
