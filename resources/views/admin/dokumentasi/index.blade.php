@extends('admin.layout.app')

@section('page-title', ($editItem ?? null) ? 'Edit Dokumentasi' : 'Kelola Dokumentasi Kegiatan')

@section('content')

    @php
        // $editItem dikirim dari edit() saat admin klik "Edit" -- form di
        // bawah otomatis berubah jadi mode edit (terisi data lama, action
        // ke update()).
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
                ? 'Ubah data kegiatan ini. Centang foto yang ingin dihapus dan/atau tambahkan foto baru.'
                : 'Satu kegiatan bisa memiliki beberapa foto. Dokumentasi yang diunggah akan tampil di halaman utama website.' }}
        </p>

        <form action="{{ $isEdit ? route('admin.dokumentasi.update', $editItem->id) : route('admin.dokumentasi.store') }}"
              method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    <x-admin.field label="Judul Kegiatan">
        <input
            type="text"
            name="judul_kegiatan"
            placeholder="Contoh: Workshop Pengembangan Keahlian Digital"
            required
            value="{{ old('judul_kegiatan', $editItem->judul_kegiatan ?? '') }}"
            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition"
        >
    </x-admin.field>

    <x-admin.field label="Kategori">
        <input
            type="text"
            name="kategori_badge"
            placeholder="Contoh: Pelatihan"
            required
            value="{{ old('kategori_badge', $editItem->kategori_badge ?? '') }}"
            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition"
        >
    </x-admin.field>

    <x-admin.field label="Tempat Pelaksanaan">
        <input
            type="text"
            name="tempat_pelaksanaan"
            placeholder="Contoh: Dinas Kominfo Kabupaten Ponorogo"
            required
            value="{{ old('tempat_pelaksanaan', $editItem->tempat_pelaksanaan ?? '') }}"
            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition"
        >
    </x-admin.field>

    <x-admin.field label="Tanggal Pelaksanaan">
        <input
            type="date"
            name="tanggal_pelaksanaan"
            required
            value="{{ old('tanggal_pelaksanaan', isset($editItem->tanggal_pelaksanaan) ? $editItem->tanggal_pelaksanaan->format('Y-m-d') : '') }}"
            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition"
        >
    </x-admin.field>

</div>

            {{-- DOKUMENTASI FOTO --}}
<div class="space-y-4">

    <div>
        <label class="block text-sm font-bold text-slate-800">
            Dokumentasi Kegiatan
        </label>
        <p class="mt-1 text-xs text-slate-500">
            Setiap foto memiliki uraian masing-masing. Maksimal 10 foto, ukuran maksimal 2MB per foto.
        </p>
    </div>

    {{-- FOTO LAMA SAAT EDIT --}}
    @if($isEdit && $editItem->fotos->isNotEmpty())
        <div class="space-y-4">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                Dokumentasi yang Sudah Tersimpan
            </p>

            @foreach($editItem->fotos as $foto)
                <div
                    class="relative rounded-2xl border border-slate-200 bg-slate-50 p-4"
                    data-existing-photo
                    data-photo-id="{{ $foto->id }}"
                >
                    <div class="grid grid-cols-1 md:grid-cols-[180px_1fr_auto] gap-4 items-start">

                        {{-- Preview foto lama --}}
                        <div class="relative">
                            <img
                                src="{{ $foto->url }}"
                                alt="Foto dokumentasi"
                                class="w-full md:w-44 h-32 object-cover rounded-xl border border-slate-200 bg-white"
                            >

                            <span
                                class="absolute top-2 left-2 px-2 py-1 rounded-lg bg-slate-900/70 text-white text-[10px] font-bold"
                            >
                                Foto tersimpan
                            </span>
                        </div>

                        {{-- Uraian foto lama --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Uraian Foto
                            </label>

                            <textarea
                                name="uraian_lama[{{ $foto->id }}]"
                                rows="4"
                                placeholder="Jelaskan kegiatan yang terlihat pada foto ini..."
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition"
                            >{{ old("uraian_lama.{$foto->id}", $foto->uraian ?? '') }}</textarea>
                        </div>

                        {{-- Tombol hapus --}}
                        <label class="inline-flex md:flex-col items-center justify-center gap-2 cursor-pointer text-xs font-bold text-rose-600">
                            <input
                                type="checkbox"
                                name="hapus_foto[]"
                                value="{{ $foto->id }}"
                                class="peer sr-only"
                            >

                            <span
                                class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 bg-white text-slate-400 peer-checked:bg-rose-50 peer-checked:border-rose-300 peer-checked:text-rose-600 transition"
                            >
                                🗑️
                            </span>

                            <span class="peer-checked:text-rose-600">
                                Hapus
                            </span>
                        </label>

                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- FOTO BARU --}}
    <div>
        <div class="flex items-center justify-between gap-3 mb-3">
            <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                Tambahkan Dokumentasi
            </p>

            <span
                id="jumlahFotoBaru"
                class="text-[11px] font-semibold text-slate-400"
            >
                0 foto baru
            </span>
        </div>

        <div
            id="fotoBaruContainer"
            class="space-y-4"
        >
            {{-- Item foto baru dibuat melalui JavaScript --}}
        </div>

        <button
            type="button"
            id="tambahFotoBtn"
            class="mt-3 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-dashed border-blue-300 bg-blue-50/50 hover:bg-blue-50 text-blue-700 text-sm font-bold transition"
        >
            <span class="text-lg leading-none">+</span>
            Tambah Dokumentasi
        </button>

        <p class="mt-2 text-xs text-slate-400">
            Kamu dapat menambahkan maksimal 10 foto secara keseluruhan.
        </p>
    </div>

</div>

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
                        @if($item->sampul_url)
                            <div class="relative shrink-0">
                                <img src="{{ $item->sampul_url }}" alt=""
                                     class="w-20 h-20 object-cover rounded-xl border border-slate-200 shadow-sm">
                                @if($item->fotos->count() > 1)
                                    <span class="absolute -bottom-1.5 -right-1.5 text-[10px] font-bold text-white bg-slate-800 px-1.5 py-0.5 rounded-full">
                                        📷 {{ $item->fotos->count() }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <div class="w-20 h-20 shrink-0 rounded-xl border border-slate-200 bg-slate-200 flex items-center justify-center text-[11px] text-slate-400">
                                Tanpa foto
                            </div>
                        @endif

                        <div class="space-y-1 min-w-0">
    <span class="inline-block max-w-full truncate px-2.5 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold">
        {{ $item->kategori_badge }}
    </span>

    <h4 class="font-extrabold text-slate-900 text-base pt-1 truncate">
        {{ $item->judul_kegiatan }}
    </h4>

    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
        <span class="inline-flex items-center gap-1">
            📍 {{ $item->tempat_pelaksanaan ?: 'Tempat belum diisi' }}
        </span>

        <span class="inline-flex items-center gap-1">
            📅
            {{ $item->tanggal_pelaksanaan
                ? $item->tanggal_pelaksanaan->translatedFormat('d F Y')
                : 'Tanggal belum diisi' }}
        </span>
    </div>
</div>
                    </div>

                    <div class="flex items-center gap-2 self-end md:self-center shrink-0">
                        <a href="{{ route('dokumentasi.show', $item->id) }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                            Lihat
                        </a>
                        <a href="{{ route('admin.dokumentasi.edit', $item->id) }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-xl text-xs font-bold transition">
                            ✏️ Edit
                        </a>
                        <x-admin.delete-button
                            :action="route('admin.dokumentasi.destroy', $item->id)"
                            confirm="Apakah Anda yakin ingin menghapus dokumentasi kegiatan ini beserta seluruh fotonya?"
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
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('fotoBaruContainer');
    const tambahBtn = document.getElementById('tambahFotoBtn');
    const jumlahLabel = document.getElementById('jumlahFotoBaru');

    if (!container || !tambahBtn) {
        return;
    }

    const MAX_FOTO = 10;

    function jumlahFotoLamaTersisa() {
        return document.querySelectorAll(
            '[data-existing-photo]:not(:has(input[name="hapus_foto[]"]:checked))'
        ).length;
    }

    function updateJumlah() {
        const jumlahBaru = container.querySelectorAll('[data-new-photo]').length;
        const jumlahLama = jumlahFotoLamaTersisa();
        const total = jumlahLama + jumlahBaru;

        jumlahLabel.textContent = `${jumlahBaru} foto baru`;

        if (total >= MAX_FOTO) {
            tambahBtn.disabled = true;
            tambahBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            tambahBtn.disabled = false;
            tambahBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    function buatFotoBaru() {
        const item = document.createElement('div');

        item.setAttribute('data-new-photo', '');

        item.className = `
            rounded-2xl
            border border-blue-100
            bg-blue-50/40
            p-4
        `;

        item.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-[180px_1fr_auto] gap-4 items-start">

                {{-- Preview --}}
                <div>
                    <div
                        class="preview-wrapper hidden relative"
                    >
                        <img
                            src=""
                            alt="Preview foto"
                            class="w-full md:w-44 h-32 object-cover rounded-xl border border-slate-200 bg-white"
                        >

                        <span
                            class="absolute top-2 left-2 px-2 py-1 rounded-lg bg-blue-600/80 text-white text-[10px] font-bold"
                        >
                            Foto baru
                        </span>
                    </div>

                    <div
                        class="empty-preview w-full md:w-44 h-32 rounded-xl border border-dashed border-slate-300 bg-white flex items-center justify-center text-xs text-slate-400"
                    >
                        Belum ada foto
                    </div>
                </div>

                {{-- Input foto + uraian --}}
                <div class="space-y-4">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Pilih Foto
                        </label>

                        <input
                            type="file"
                            name="foto[]"
                            accept="image/jpeg,image/png,image/webp"
                            required
                            class="foto-input w-full text-sm text-slate-500
                                   file:mr-4 file:py-2.5 file:px-5
                                   file:rounded-xl file:border-0
                                   file:font-bold file:bg-blue-100
                                   file:text-blue-700
                                   hover:file:bg-blue-200
                                   border border-slate-200
                                   rounded-xl bg-white"
                        >

                        <p class="mt-1 text-[11px] text-slate-400">
                            JPG, PNG, atau WEBP. Maksimal 2MB.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">
                            Uraian Foto
                        </label>

                        <textarea
                            name="uraian[]"
                            rows="4"
                            required
                            placeholder="Jelaskan kegiatan yang terlihat pada foto ini..."
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-white
                                   focus:border-blue-400 focus:ring-2 focus:ring-blue-100
                                   outline-none transition"
                        ></textarea>
                    </div>

                </div>

                {{-- Hapus foto baru --}}
                <button
                    type="button"
                    class="hapus-foto-baru inline-flex items-center justify-center
                           w-10 h-10 rounded-xl border border-slate-200
                           bg-white text-slate-400 hover:bg-rose-50
                           hover:border-rose-200 hover:text-rose-600 transition"
                    title="Hapus foto ini"
                >
                    🗑️
                </button>

            </div>
        `;

        const fileInput = item.querySelector('.foto-input');
        const previewWrapper = item.querySelector('.preview-wrapper');
        const previewImage = item.querySelector('.preview-wrapper img');
        const emptyPreview = item.querySelector('.empty-preview');
        const hapusBtn = item.querySelector('.hapus-foto-baru');

        fileInput.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) {
                previewWrapper.classList.add('hidden');
                emptyPreview.classList.remove('hidden');
                previewImage.src = '';
                return;
            }

            if (!file.type.startsWith('image/')) {
                this.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {
                previewImage.src = event.target.result;
                previewWrapper.classList.remove('hidden');
                emptyPreview.classList.add('hidden');
            };

            reader.readAsDataURL(file);
        });

        hapusBtn.addEventListener('click', function () {
            item.remove();
            updateJumlah();
        });

        container.appendChild(item);

        updateJumlah();
    }

    tambahBtn.addEventListener('click', function () {
        const jumlahLama = jumlahFotoLamaTersisa();
        const jumlahBaru = container.querySelectorAll('[data-new-photo]').length;

        if (jumlahLama + jumlahBaru >= MAX_FOTO) {
            return;
        }

        buatFotoBaru();
    });

    document.addEventListener('change', function (event) {
        if (event.target.matches('input[name="hapus_foto[]"]')) {
            updateJumlah();
        }
    });

    /*
     * Saat tambah data baru, otomatis tampilkan
     * satu form foto pertama.
     */
    @if(!$isEdit)
        buatFotoBaru();
    @endif

    updateJumlah();
});
</script>
@endpush