
{{-- Komponen: <x-form.file name="surat_pengantar" label="Surat Pengantar Instansi" required accept=".pdf" /> --}}
@props(['label', 'name', 'required' => false, 'hint' => null])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
@endphp

<div class="p-5 rounded-2xl border transition
            {{ $hasError
                ? 'border-rose-400 bg-rose-50/70 dark:border-rose-500/50 dark:bg-rose-500/10'
                : 'border-dashed border-blue-200 dark:border-slate-600 bg-blue-50/50 dark:bg-slate-900/50 hover:bg-blue-50 dark:hover:bg-slate-800/70 hover:border-blue-300 dark:hover:border-blue-500/60' }}">

    <label for="{{ $id }}"
           class="block text-sm font-semibold text-slate-900 dark:text-slate-100 mb-2">
        {{ $label }}

        @if($required)
            <span class="text-rose-500">*</span>
        @else
            <span class="text-slate-500 dark:text-slate-400 font-normal">(Opsional)</span>
        @endif
    </label>

    <input
        type="file"
        name="{{ $name }}"
        id="{{ $id }}"
        @if($required) required @endif
        {{ $attributes->except(['id'])->merge([
            'class' => 'w-full text-xs text-slate-600 dark:text-slate-300
                       file:mr-3 file:py-2.5 file:px-4
                       file:rounded-xl file:border-0
                       file:text-xs file:font-bold
                       file:bg-blue-100 file:text-blue-700
                       hover:file:bg-blue-200
                       dark:file:bg-blue-500/15 dark:file:text-cyan-300
                       dark:hover:file:bg-blue-500/25
                       cursor-pointer'
        ]) }}
    >

    @error($name)
        <p class="text-xs text-rose-600 dark:text-rose-400 mt-2 font-medium">
            {{ $message }}
        </p>
    @enderror

    @if($hint)
        <p class="text-xs text-slate-600 dark:text-slate-400 mt-2">
            {{ $hint }}
        </p>
    @endif

</div>
