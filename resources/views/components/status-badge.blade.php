{{-- <x-status-badge :status="$item->status" /> -- satu sumber warna & label status pendaftaran. --}}
@props(['status'])

@php
    $peta = [
        'draft'    => ['Draft',               'bg-slate-100 text-slate-600 border-slate-200'],
        'pending'  => ['Menunggu Verifikasi', 'bg-blue-50 text-blue-700 border-blue-100'],
        'revisi'   => ['Perlu Revisi',        'bg-orange-50 text-orange-700 border-orange-100'],
        'diterima' => ['Diterima',            'bg-emerald-50 text-emerald-700 border-emerald-100'],
        'ditolak'  => ['Ditolak',             'bg-rose-50 text-rose-700 border-rose-100'],
        'selesai'  => ['Selesai',             'bg-indigo-50 text-indigo-700 border-indigo-100'],
    ];

    [$label, $warna] = $peta[$status] ?? [ucfirst($status), 'bg-slate-100 text-slate-600 border-slate-200'];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center px-2.5 py-0.5 rounded-full border text-[11px] font-semibold',
    $warna,
]) }}>{{ $label }}</span>