{{--
    Komponen: <x-admin.delete-button :action="route('admin.instansi.destroy', $instansi->id)" confirm="Yakin ingin menghapus instansi ini?" />
              <x-admin.delete-button :action="route('admin.dokumentasi.destroy', $item->id)" confirm="..." icon="🗑️" size="md" class="self-end md:self-center" />
--}}
@props(['action', 'confirm', 'label' => 'Hapus', 'icon' => null, 'size' => 'sm'])

@php
    $sizeClass = $size === 'md' ? 'px-4 py-2' : 'px-3 py-1.5';
@endphp

<form action="{{ $action }}" method="POST" onsubmit="return confirm('{{ $confirm }}')" {{ $attributes }}>
    @csrf
    @method('DELETE')
    <button type="submit" class="{{ $sizeClass }} bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-xs font-bold transition {{ $icon ? 'inline-flex items-center gap-1.5' : '' }}">
        @if($icon){{ $icon }} @endif{{ $label }}
    </button>
</form>