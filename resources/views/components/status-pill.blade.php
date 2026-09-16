{{--
    Komponen: <x-status-pill status="pending" />  -> "MENUNGGU REVIEW" (biru)
              <x-status-pill status="diterima" /> -> "DITERIMA" (hijau)
              <x-status-pill status="ditolak" />  -> "DITOLAK" (merah)

--}}
@props(['status'])

@php
    $map = [
        'pending'  => ['bg' => 'bg-blue-100 text-blue-700', 'label' => 'Menunggu Review'],
        'diterima' => ['bg' => 'bg-emerald-100 text-emerald-700', 'label' => 'Diterima'],
        'ditolak'  => ['bg' => 'bg-red-100 text-red-700', 'label' => 'Ditolak'],
    ];
    $s = $map[$status] ?? ['bg' => 'bg-slate-100 text-slate-700', 'label' => ucfirst($status)];
@endphp

<span class="inline-block px-2.5 py-0.5 {{ $s['bg'] }} text-[10px] font-extrabold rounded-full tracking-wide uppercase">
    {{ $s['label'] }}
</span>
