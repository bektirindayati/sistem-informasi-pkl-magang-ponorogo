{{--
    Kepala halaman standar.

    <x-page-header eyebrow="Pendaftaran" title="Status Pendaftaran"
                   :back="route('user.dashboard')" back-label="Kembali ke Dashboard">
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
    @if($back)
        <a href="{{ $back }}"
           class="inline-block text-sm font-medium text-slate-500 hover:text-blue-600 transition">
            {{ $backLabel }}
        </a>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div class="min-w-0">
            @if($eyebrow)
                <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">{{ $eyebrow }}</p>
            @endif

            <h1 class="mt-1 text-xl sm:text-2xl font-bold tracking-tight text-slate-900">
                {{ $title }}
            </h1>

            @if(trim((string) $slot) !== '')
                <p class="mt-1.5 text-sm text-slate-500 leading-relaxed max-w-2xl">
                    {{ $slot }}
                </p>
            @endif
        </div>

        @isset($action)
            <div class="shrink-0">{{ $action }}</div>
        @endisset
    </div>
</div>