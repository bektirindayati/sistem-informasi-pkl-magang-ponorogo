{{--
    Komponen: <x-admin.file-link :href="asset('storage/'.$item->surat_pengantar)" icon="📄" label="Surat Pengantar" color="blue" />
              <x-admin.file-link :href="$item->proposal ? asset('storage/'.$item->proposal) : null" icon="📑" label="Proposal Magang" color="indigo" empty-text="Tidak ada proposal" />

    target="_blank" -> file terbuka di tab baru, tidak menimpa halaman ini.
    rel="noopener noreferrer" -> praktik keamanan standar untuk target="_blank",
    mencegah tab baru punya akses ke window.opener tab asal.

    FIX: warna sebelumnya dirakit dinamis lewat "bg-{{ $color }}-50" dkk.
    
--}}
@props(['href' => null, 'label', 'icon' => '📄', 'color' => 'blue', 'emptyText' => 'Tidak tersedia'])

@php
    $colorClasses = match($color) {
        'indigo' => 'bg-indigo-50 text-indigo-600 hover:bg-indigo-100',
        'emerald' => 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100',
        'rose' => 'bg-rose-50 text-rose-600 hover:bg-rose-100',
        'amber' => 'bg-amber-50 text-amber-600 hover:bg-amber-100',
        default => 'bg-blue-50 text-blue-600 hover:bg-blue-100',
    };
@endphp

@if($href)
    <a href="{{ $href }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 {{ $colorClasses }} rounded-xl font-bold text-xs transition">
        {{ $icon }} {{ $label }}
    </a>
@else
    <span class="inline-flex items-center px-2.5 py-1 bg-slate-100 text-slate-400 rounded-lg text-xs font-semibold">
        {{ $emptyText }}
    </span>
@endif