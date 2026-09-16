{{-- Komponen: <x-alert type="success"> {{ session('success') }}</x-alert>
              <x-alert type="error"><ul>...daftar error...</ul></x-alert>--}}
@props(['type' => 'success'])

@php
    $styles = [
        'success' => 'bg-emerald-50 border-emerald-200 text-emerald-700',
        'error'   => 'bg-rose-50 border-rose-200 text-rose-700',
    ];
@endphp

<div class="p-4 border {{ $styles[$type] ?? $styles['success'] }} rounded-2xl text-sm font-semibold">
    {{ $slot }}
</div>