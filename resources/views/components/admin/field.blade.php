{{--Komponen: <x-admin.field label="Judul Kegiatan / Badge (Atas)">
                  <input type="text" name="judul_kegiatan" class="..." required>
              </x-admin.field>

    <x-form.input>--}}
@props(['label'])

<div>
    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">{{ $label }}</label>
    {{ $slot }}
</div>