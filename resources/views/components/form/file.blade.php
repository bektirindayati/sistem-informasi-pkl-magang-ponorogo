{{--Komponen: <x-form.file name="surat_pengantar" label="Surat Pengantar Instansi" required accept=".pdf" />--}}
@props(['label', 'name', 'required' => false, 'hint' => null])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
@endphp

<div class="p-5 border rounded-2xl transition
            {{ $hasError
                ? 'border-rose-500 bg-rose-50/30 dark:bg-rose-500/10'
                : 'border-dashed border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100/50 dark:hover:bg-slate-700/40' }}">
    <label for="{{ $id }}" class="block text-sm font-semibold text-slate-800 dark:text-slate-100 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-rose-500">*</span>
        @else
            <span class="text-slate-400 dark:text-slate-500 font-normal">(Opsional)</span>
        @endif
    </label>

    <input
        type="file"
        name="{{ $name }}"
        id="{{ $id }}"
        @if($required) required @endif
        {{ $attributes->except(['id'])->merge([
            'class' => 'w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-500/15 dark:file:text-indigo-300 dark:hover:file:bg-indigo-500/25 cursor-pointer'
        ]) }}
    >

    @error($name)
        <p class="text-xs text-rose-600 dark:text-rose-400 mt-2 font-medium">{{ $message }}</p>
    @enderror

    @if($hint)
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">{{ $hint }}</p>
    @endif
</div>