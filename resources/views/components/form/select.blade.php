{{-- resources/views/components/form/select.blade.php --}}

@props([
    'label',
    'name',
    'required' => false,
    'hint' => null,
])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
@endphp

<div>
    {{-- Label --}}
    <label
        for="{{ $id }}"
        class="block text-sm font-semibold
               text-slate-700 dark:text-slate-200
               mb-1.5"
    >
        {{ $label }}

        @if ($required)
            <span class="text-rose-500">*</span>
        @endif
    </label>

    {{-- Select --}}
    <select
        name="{{ $name }}"
        id="{{ $id }}"
        @if ($required) required @endif
        {{ $attributes->except(['id', 'class'])->merge([
            'class' =>
                'w-full px-4 py-3 rounded-xl border text-sm font-medium ' .
                'outline-none transition-colors duration-200 ' .

                ($hasError
                    ? 'border-rose-500 bg-rose-50/50 dark:bg-rose-500/10 dark:border-rose-500'
                    : 'border-slate-300 bg-white dark:border-slate-600 dark:bg-slate-900') .

                ' text-slate-800 dark:text-slate-100' .
                ' focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500' .
                ' dark:focus:ring-cyan-500/20 dark:focus:border-cyan-500' .

                ' shadow-sm dark:shadow-none',
        ]) }}
    >
        {{ $slot }}
    </select>

    {{-- Pesan validasi --}}
    @error($name)
        <p class="text-xs text-rose-600 dark:text-rose-400 mt-1.5 font-medium">
            {{ $message }}
        </p>
    @enderror

    {{-- Keterangan tambahan --}}
    @if ($hint)
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
            {{ $hint }}
        </p>
    @endif
</div>