
{{--
    Komponen: <x-status-pill status="pending" />  -> "MENUNGGU REVIEW" (biru)
              <x-status-pill status="diterima" /> -> "DITERIMA" (hijau)
              <x-status-pill status="ditolak" />  -> "DITOLAK" (merah)
--}}
@props(['status'])

@php
    $map = [
        'pending' => [
            'bg' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-cyan-300 dark:border-blue-500/30',
            'label' => 'Menunggu Review',
        ],
        'diterima' => [
            'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-500/30',
            'label' => 'Diterima',
        ],
        'ditolak' => [
            'bg' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-500/10 dark:text-rose-300 dark:border-rose-500/30',
            'label' => 'Ditolak',
        ],
    ];

    $s = $map[$status] ?? [
        'bg' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-700/40 dark:text-slate-300 dark:border-slate-600',
        'label' => ucfirst($status),
    ];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center px-2.5 py-1 rounded-full border',
    'text-[10px] font-extrabold tracking-wide uppercase',
    'whitespace-nowrap',
    $s['bg'],
]) }}>
    {{ $s['label'] }}
</span>
