{{-- resources/views/components/galeri-card.blade.php --}}
@props([
    'tag'       => null,
    'judul'     => '',
    'gambar'    => null,
    'tempat'    => null,
    'tanggal'   => null,
    'ringkasan' => null,
])

<article {{ $attributes->class([
    'group flex h-full flex-col overflow-hidden rounded-2xl border bg-white transition duration-200',
    'border-slate-200 hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg hover:shadow-blue-100/60',
    'dark:border-slate-800 dark:bg-slate-900 dark:hover:border-blue-500/50 dark:hover:shadow-none',
]) }}>

    {{-- Foto --}}
    <div class="relative aspect-[16/10] overflow-hidden bg-slate-100 dark:bg-slate-800">
        @if($gambar)
            <img src="{{ $gambar }}" alt="{{ $judul }}" loading="lazy"
                 class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center text-4xl text-slate-300 dark:text-slate-600">📷</div>
        @endif

        @if($tag)
            <span class="absolute left-3 top-3 max-w-[calc(100%-1.5rem)] truncate rounded-full bg-white/90 px-3 py-1
                         text-[11px] font-bold uppercase tracking-wide text-blue-700 backdrop-blur
                         dark:bg-slate-900/85 dark:text-blue-300">
                {{ $tag }}
            </span>
        @endif
    </div>

    {{-- Isi --}}
    <div class="flex flex-1 flex-col p-5">

        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-300">
            @if($tanggal)
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('j F Y') }}
                </span>
            @endif

            @if($tempat)
                <span class="inline-flex min-w-0 items-center gap-1.5">
                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
                    </svg>
                    <span class="truncate">{{ $tempat }}</span>
                </span>
            @endif
        </div>

        <h3 class="mt-3 line-clamp-2 text-lg font-bold leading-snug text-slate-900 transition-colors
                   group-hover:text-blue-700 dark:text-white dark:group-hover:text-blue-300">
            {{ $judul }}
        </h3>

        @if($ringkasan)
            <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                {{ $ringkasan }}
            </p>
        @endif

        <span class="mt-auto inline-flex items-center gap-1 pt-4 text-sm font-bold text-blue-600 dark:text-blue-400">
            Baca selengkapnya
            <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none"
                 stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </span>
    </div>
</article>