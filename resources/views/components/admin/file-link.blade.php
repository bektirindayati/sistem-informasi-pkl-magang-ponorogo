{{-- <x-admin.file-link :href="asset('storage/'.$item->surat_pengantar)" label="Surat Pengantar" color="blue" empty-text="Tidak ada" /> --}}
@props(['href' => null, 'label', 'color' => 'blue', 'emptyText' => 'Tidak tersedia'])

@php
    $warna = [
        'indigo'  => 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-300 dark:hover:bg-indigo-500/20',
        'emerald' => 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-300 dark:hover:bg-emerald-500/20',
        'rose'    => 'bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-300 dark:hover:bg-rose-500/20',
        'amber'   => 'bg-amber-50 text-amber-700 hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-300 dark:hover:bg-amber-500/20',
        'blue'    => 'bg-blue-50 text-blue-700 hover:bg-blue-100 dark:bg-blue-500/10 dark:text-blue-300 dark:hover:bg-blue-500/20',
    ][$color] ?? 'bg-blue-50 text-blue-700 hover:bg-blue-100 dark:bg-blue-500/10 dark:text-blue-300 dark:hover:bg-blue-500/20';
@endphp

@if ($href)
    <a href="{{ $href }}" target="_blank" rel="noopener noreferrer"
       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold transition {{ $warna }}">
        <x-ikon name="document" class="h-4 w-4" />
        {{ $label }}
    </a>
@else
    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-medium bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
        {{ $emptyText }}
    </span>
@endif