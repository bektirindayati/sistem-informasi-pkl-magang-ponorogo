{{-- resources/views/components/admin/table-card.blade.php --}}
@props([
    'title'       => '',
    'description' => null,
    'icon'        => null,
    'titleSize'   => 'text-base md:text-lg',
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-none overflow-hidden']) }}>

    {{-- Header kartu --}}
    <div class="p-5 md:p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between gap-4">
        <div class="min-w-0">
            <h3 class="font-extrabold text-slate-900 dark:text-white {{ $titleSize }} flex items-center gap-2">
                @if($icon)
                    <span>{{ $icon }}</span>
                @endif
                <span class="truncate">{{ $title }}</span>
            </h3>

            @if($description)
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-300 font-medium mt-1">
                    {{ $description }}
                </p>
            @endif
        </div>

        {{-- Slot opsional untuk tombol di kanan header: <x-slot:actions>...</x-slot:actions> --}}
        @isset($actions)
            <div class="shrink-0">{{ $actions }}</div>
        @endisset
    </div>

    {{-- Isi tabel --}}
    <div class="overflow-x-auto">
        {{ $slot }}
    </div>
</div>