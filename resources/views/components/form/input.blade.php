{{-- resources/views/components/form/input.blade.php --}}

@props([
    'label',
    'name',
    'type' => 'text',
    'required' => false,
    'hint' => null,
    'value' => null,
])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
@endphp

<div>
    {{-- Label --}}
    <label
        for="{{ $id }}"
        class="block text-[13px] font-semibold
               text-slate-600 dark:text-slate-300
               mb-1.5"
    >
        {{ $label }}

        @if ($required)
            <span class="text-rose-500">*</span>
        @endif
    </label>

    {{-- Input (tinggi tetap h-11 agar sama dengan select & kolom pencarian) --}}
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        value="{{ old($name, $value) }}"
        @if ($required) required @endif
        {{ $attributes->except(['id', 'value', 'class'])->merge([
            'class' =>
                'w-full h-11 px-4 rounded-xl border text-[15px] font-medium ' .
                'outline-none transition-colors duration-200 ' .

                ($hasError
                    ? 'border-rose-500 bg-rose-50/50 dark:bg-rose-500/10 dark:border-rose-500'
                    : 'border-slate-300 bg-white dark:border-slate-600 dark:bg-slate-900') .

                '' .
                ' text-slate-900 dark:text-slate-100 placeholder:font-normal placeholder:text-slate-400 dark:placeholder:text-slate-500 hover:border-slate-400' .

                ' read-only:bg-slate-50 dark:read-only:bg-slate-800' .
                ' read-only:text-slate-500 dark:read-only:text-slate-300' .
                ' read-only:cursor-not-allowed' .

                ' focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500' .
                ' dark:focus:ring-cyan-500/20 dark:focus:border-cyan-500' .

                ' shadow-sm dark:shadow-none',
        ]) }}
    >

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