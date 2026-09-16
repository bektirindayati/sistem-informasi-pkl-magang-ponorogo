{{--
    Komponen: <x-form.select name="kategori" label="Kategori Pendaftar" required>
                  <option value="">-- Pilih Kategori --</option>
                  <option value="Mahasiswa" @selected(old('kategori')=='Mahasiswa')>Mahasiswa</option>
              </x-form.select>

    Isi <option> tetap kamu tulis sendiri di dalam slot (supaya daftar
    dinas/kategori yang panjang tetap gampang dibaca & diedit), tapi
    label + wrapper + style + @error-nya sudah otomatis.
--}}
@props(['label', 'name', 'required' => false, 'hint' => null])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
@endphp

<div>
    <label for="{{ $id }}" class="block text-sm font-bold text-slate-800 mb-2">
        {{ $label }} @if($required)<span class="text-rose-500">*</span>@endif
    </label>

    <select
        name="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->except(['id', 'class'])->merge([
            'id' => $id,
            'class' => 'w-full px-4 py-3 rounded-xl border '
                . ($hasError ? 'border-rose-500 bg-rose-50/30' : 'border-slate-300 bg-white')
                . ' focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition duration-150 text-sm text-slate-800 font-medium shadow-sm'
        ]) }}
    >
        {{ $slot }}
    </select>

    @error($name)
        <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
    @enderror

    @if($hint)
        <p class="text-xs text-slate-500 mt-2">{{ $hint }}</p>
    @endif
</div>
