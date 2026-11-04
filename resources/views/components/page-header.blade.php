{{--
    Kepala halaman standar.

    <x-page-header
        eyebrow="Pendaftaran"
        title="Status Pendaftaran"
        :back="route('user.dashboard')"
        back-label="Kembali ke Dashboard"
    >
        Teks penjelasan singkat (slot utama).
        <x-slot:action> ...tombol di kanan... </x-slot:action>
    </x-page-header>
--}}

@props([
    'title',
    'eyebrow' => null,
    'back' => null,
    'backLabel' => 'Kembali ke Dashboard',
])

<div class="space-y-3">

    {{-- Tombol kembali --}}
    @if ($back)
        <a href="{{ $back }}"
           class="inline-flex items-center gap-2 text-sm font-semibold
                  text-slate-600 hover:text-blue-600
                  dark:text-slate-300 dark:hover:text-cyan-400
                  transition-colors duration-200">

            <svg class="w-4 h-4 shrink-0"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 19l-7-7 7-7"/>
            </svg>

            <span>{{ $backLabel }}</span>
        </a>
    @endif

    {{-- Judul dan deskripsi --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">

        <div class="min-w-0">

            {{-- Label kecil di atas judul --}}
            @if ($eyebrow)
                <p class="text-xs font-bold uppercase tracking-wider
                          text-blue-700 dark:text-cyan-300">
                    {{ $eyebrow }}
                </p>
            @endif

            {{-- Judul halaman --}}
            <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold tracking-tight
                       text-slate-900 dark:text-white">
                {{ $title }}
            </h1>

            {{-- Deskripsi --}}
            @if (trim((string) $slot) !== '')
                <p class="mt-2 text-sm sm:text-base leading-relaxed max-w-2xl
                          text-slate-600 dark:text-slate-300">
                    {{ $slot }}
                </p>
            @endif

        </div>

        {{-- Tombol tambahan di sisi kanan --}}
        @isset ($action)
            <div class="shrink-0">
                {{ $action }}
            </div>
        @endisset

    </div>
</div>