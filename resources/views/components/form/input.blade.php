{{--Komponen: <x-form.input name="nama_lengkap" label="Nama Lengkap" required :value="Auth::user()->name ?? ''" />

    Menggantikan pola label + input + @error yang sebelumnya ditulis
    ulang manual untuk SETIAP field (nama, NIM/NISN, instansi, jurusan,
    no HP, alamat, kabupaten, provinsi, tanggal, dst).

    Atribut tambahan (id, oninput, onchange, min, list, readonly, dst)
    otomatis diteruskan ke <input> lewat $attributes — jadi behavior
    JS yang sudah kamu tulis (auto-fill provinsi, filter angka no HP,
    dst) tetap jalan seperti biasa, tinggal ditulis sebagai atribut
    biasa saat memanggil komponennya.
--}}
@props(['label', 'name', 'type' => 'text', 'required' => false, 'hint' => null, 'value' => null])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
@endphp

<div>
    <label for="{{ $id }}" class="block text-sm font-semibold text-slate-700 mb-1.5">
        {{ $label }} @if($required)<span class="text-rose-500">*</span>@endif
    </label>

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        value="{{ old($name, $value) }}"
        @if($required) required @endif
        {{ $attributes->except(['id', 'value', 'class'])->merge([
            'class' => 'w-full px-4 py-3 rounded-xl border '
                . ($hasError ? 'border-rose-500 bg-rose-50/30' : 'border-slate-300')
                . ' focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition duration-150 text-sm shadow-sm'
        ]) }}
    >

    @error($name)
        <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
    @enderror

    @if($hint)
        <p class="text-xs text-slate-500 mt-2">{{ $hint }}</p>
    @endif
</div>
