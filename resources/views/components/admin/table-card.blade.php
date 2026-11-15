{{-- resources/views/components/admin/table-card.blade.php --}}
@props([
    'title'       => '',
    'description' => null,
    'icon'        => null,   // dipertahankan agar pemanggilan lama tidak error; tidak lagi ditampilkan
    'titleSize'   => 'text-lg',
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-none overflow-hidden']) }}>

    {{-- Header kartu --}}
    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-x-4 gap-y-3">
        <div class="min-w-0">
            <h3 class="font-extrabold text-slate-900 dark:text-white {{ $titleSize }} truncate">{{ $title }}</h3>

            @if($description)
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $description }}</p>
            @endif
        </div>

        {{-- Slot opsional untuk tombol/badge di kanan header: <x-slot:actions>...</x-slot:actions> --}}
        @isset($actions)
            <div class="shrink-0">{{ $actions }}</div>
        @endisset
    </div>

    {{-- Isi tabel --}}
    <div class="overflow-x-auto">
        {{ $slot }}
    </div>
</div>