@extends('admin.layout.app')

@section('page-title', ($editItem ?? null) ? 'Edit Dokumentasi' : 'Kelola Dokumentasi Kegiatan')

@section('content')

    @php
        // BARU: $editItem dikirim dari method edit() saat admin klik
        // "Edit" pada salah satu item -- form di bawah otomatis berubah
        // jadi mode edit (terisi data lama, action ke update()).
        $isEdit = isset($editItem) && $editItem;
    @endphp

    @if(session('success'))
        <x-alert type="success"> {{ session('success') }}</x-alert>
    @endif

    @if ($errors->any())
        <x-alert type="error">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    {{-- Form Tambah / Edit Dokumentasi --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm {{ $isEdit ? 'ring-2 ring-amber-200' : '' }}">
        <div class="flex items-start justify-between gap-4 mb-1">
            <h3 class="font-extrabold text-slate-900 text-lg">
                {{ $isEdit ? '✏️ Edit Dokumentasi' : 'Unggah Dokumentasi Baru' }}
            </h3>
            @if($isEdit)
                <a href="{{ route('admin.dokumentasi.index') }}"
                   class="shrink-0 text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                    Batal, Tambah Baru
                </a>
            @endif
        </div>
        <p class="text-xs text-slate-500 mb-6">
            {{ $isEdit
                ? 'Ubah data dokumentasi ini. Kosongkan bagian foto kalau tidak ingin menggantinya.'
                : 'Dokumentasi yang diunggah akan tampil secara dinamis pada halaman utama website.' }}
        </p>

        <form action="{{ $isEdit ? route('admin.dokumentasi.update', $editItem->id) : route('admin.dokumentasi.store') }}"
              method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.field label="Judul Kegiatan / Badge (Atas)">
                    <input type="text" name="judul_kegiatan" placeholder="Contoh: Workshop & Pelatihan" required
                           value="{{ old('judul_kegiatan', $editItem->judul_kegiatan ?? '') }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50">
                </x-admin.field>

                <x-admin.field label="Kategori / Sub-Judul Kartu">
                    <input type="text" name="kategori_badge" placeholder="Contoh: Pelatihan Pengembangan Keahlian" required
                           value="{{ old('kategori_badge', $editItem->kategori_badge ?? '') }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50">
                </x-admin.field>
            </div>

            <x-admin.field label="Uraian / Deskripsi Kegiatan">
                <textarea name="deskripsi" rows="3" placeholder="Tuliskan uraian atau penjelasan singkat..." required
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50">{{ old('deskripsi', $editItem->deskripsi ?? '') }}</textarea>
            </x-admin.field>

            <x-admin.field label="Pilih Foto Dokumentasi (Maks. 2MB)">
                @if($isEdit && $editItem->foto)
                    <div class="flex items-center gap-3 mb-3 p-3 bg-slate-50 border border-slate-200 rounded-xl">
                        <img src="{{ asset('storage/' . $editItem->foto) }}" class="w-14 h-14 object-cover rounded-lg border border-slate-200 shrink-0">
                        <p class="text-xs text-slate-500">Foto saat ini. Pilih file baru di bawah kalau ingin menggantinya, atau biarkan kosong untuk tetap memakai foto ini.</p>
                    </div>
                @endif
                <input type="file" name="foto" accept="image/*" {{ $isEdit ? '' : 'required' }}
                       class="w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:font-bold file:bg-blue-50 file:text-blue-700 border border-slate-200 rounded-xl bg-slate-50">
            </x-admin.field>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit"
                        class="px-6 py-3 {{ $isEdit ? 'bg-amber-500 hover:bg-amber-600' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-bold text-sm rounded-xl shadow-md transition">
                    {{ $isEdit ? 'Simpan Perubahan' : 'Simpan & Terbitkan Dokumentasi' }}
                </button>
                @if($isEdit)
                    <a href="{{ route('admin.dokumentasi.index') }}"
                       class="px-5 py-3 text-slate-500 hover:text-slate-800 font-bold text-sm transition">
                        Batal
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- List Dokumentasi --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 space-y-6">
        <h3 class="font-extrabold text-slate-900 text-lg">Daftar Dokumentasi Saat Ini</h3>

        <div class="space-y-4">
            @forelse($dokumentasis as $item)
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between p-4 border rounded-2xl bg-slate-50/70 gap-4 transition
                    {{ $isEdit && $editItem->id === $item->id ? 'border-amber-300 ring-2 ring-amber-100' : 'border-slate-100' }}">

                    <div class="flex items-center gap-4 min-w-0">
                        <img src="{{ asset('storage/' . $item->foto) }}" class="w-20 h-20 object-cover rounded-xl border border-slate-200 shadow-sm flex-shrink-0">
                        <div class="space-y-1 min-w-0">
                            <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold">{{ $item->judul_kegiatan }}</span>
                            <h4 class="font-extrabold text-slate-900 text-base pt-1">{{ $item->kategori_badge }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-2 max-w-xl">{{ $item->deskripsi }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-end md:self-center shrink-0">
                        <a href="{{ route('admin.dokumentasi.edit', $item->id) }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-xl text-xs font-bold transition">
                            ✏️ Edit
                        </a>
                        <x-admin.delete-button
                            :action="route('admin.dokumentasi.destroy', $item->id)"
                            confirm="Apakah Anda yakin ingin menghapus dokumentasi kegiatan ini?"
                            icon="🗑️" size="md" />
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-slate-400 text-sm">
                    Belum ada data dokumentasi kegiatan yang diunggah.
                </div>
            @endforelse
        </div>
    </div>

@endsection