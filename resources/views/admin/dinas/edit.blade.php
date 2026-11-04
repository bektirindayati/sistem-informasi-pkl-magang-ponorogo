@extends('admin.layout.app')

@section('page-title', 'Edit Dinas')

@section('content')

    @php
        $field = 'w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 text-sm
                  bg-slate-50 dark:bg-slate-950/60 text-slate-800 dark:text-slate-100
                  placeholder:text-slate-400 dark:placeholder:text-slate-500
                  focus:bg-white dark:focus:bg-slate-950 focus:outline-none focus:border-blue-500
                  focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/20 transition';
        $galat = 'text-xs text-rose-600 dark:text-rose-400 font-semibold mt-1';
    @endphp

    <a href="{{ route('admin.dinas.index') }}"
       class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800
              dark:text-slate-400 dark:hover:text-slate-100 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Daftar Dinas
    </a>

    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-5 md:p-6 shadow-sm dark:shadow-none mt-4">
        <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-base mb-2">
            Edit Dinas: {{ $dinas->nama_dinas }}
        </h3>

        <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">
            Perbarui informasi dinas, kop surat, dan pejabat penandatangan surat balasan.
        </p>

        <form action="{{ route('admin.dinas.update', $dinas->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.field label="Nama Dinas">
                    <input type="text" name="nama_dinas"
                           value="{{ old('nama_dinas', $dinas->nama_dinas) }}" required
                           class="{{ $field }} {{ $errors->has('nama_dinas') ? '!border-rose-500' : '' }}">
                    @error('nama_dinas') <p class="{{ $galat }}">{{ $message }}</p> @enderror
                </x-admin.field>

                <x-admin.field label="Alamat Kantor (opsional)">
                    <input type="text" name="alamat" value="{{ old('alamat', $dinas->alamat) }}"
                           placeholder="Alamat lengkap kantor (tampil di kop surat)"
                           class="{{ $field }}">
                    @error('alamat') <p class="{{ $galat }}">{{ $message }}</p> @enderror
                </x-admin.field>

                <x-admin.field label="Telepon Kantor (opsional)">
                    <input type="text" name="telepon" value="{{ old('telepon', $dinas->telepon) }}"
                           placeholder="Nomor telepon kantor"
                           class="{{ $field }}">
                    @error('telepon') <p class="{{ $galat }}">{{ $message }}</p> @enderror
                </x-admin.field>

                <x-admin.field label="Email Dinas (opsional)">
                    <input type="email" name="email" value="{{ old('email', $dinas->email) }}"
                           placeholder="email@ponorogo.go.id"
                           class="{{ $field }}">
                    @error('email') <p class="{{ $galat }}">{{ $message }}</p> @enderror
                </x-admin.field>

                <x-admin.field label="Website Dinas (opsional)">
                    <input type="text" name="website" value="{{ old('website', $dinas->website) }}"
                           placeholder="kominfo.ponorogo.go.id"
                           class="{{ $field }}">
                    @error('website') <p class="{{ $galat }}">{{ $message }}</p> @enderror
                </x-admin.field>
            </div>

            <x-admin.field label="Deskripsi Dinas (opsional)">
                <textarea name="deskripsi" rows="2" class="{{ $field }}">{{ old('deskripsi', $dinas->deskripsi) }}</textarea>
                @error('deskripsi') <p class="{{ $galat }}">{{ $message }}</p> @enderror
            </x-admin.field>

           

<div class="border-t border-slate-200 dark:border-slate-800 pt-5">
    <h4 class="font-bold text-slate-900 dark:text-slate-100 mb-1">
        Pejabat Penandatangan
    </h4>

    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
        Data pejabat digunakan sebagai nilai awal identitas penandatangan
        pada draf surat balasan. Data akan dimasukkan secara otomatis
        ke dalam template surat dan masih dapat disesuaikan.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-admin.field label="Nama Pejabat (opsional)">
            <input type="text" name="nama_pejabat"
                   value="{{ old('nama_pejabat', $dinas->nama_pejabat) }}"
                   placeholder="Nama lengkap beserta gelar"
                   class="{{ $field }}">
            @error('nama_pejabat')
                <p class="{{ $galat }}">{{ $message }}</p>
            @enderror
        </x-admin.field>

        <x-admin.field label="Jabatan Pejabat (opsional)">
            <input type="text" name="jabatan_pejabat"
                   value="{{ old('jabatan_pejabat', $dinas->jabatan_pejabat) }}"
                   placeholder="Contoh: Kepala Dinas Komunikasi Informatika dan Statistik"
                   class="{{ $field }}">
            @error('jabatan_pejabat')
                <p class="{{ $galat }}">{{ $message }}</p>
            @enderror
        </x-admin.field>

        <x-admin.field label="Pangkat (opsional)">
            <input type="text" name="pangkat_pejabat"
                   value="{{ old('pangkat_pejabat', $dinas->pangkat_pejabat) }}"
                   placeholder="Contoh: Pembina Utama Muda"
                   class="{{ $field }}">
            @error('pangkat_pejabat')
                <p class="{{ $galat }}">{{ $message }}</p>
            @enderror
        </x-admin.field>

        <x-admin.field label="Golongan (opsional)">
            <input type="text" name="golongan_pejabat"
                   value="{{ old('golongan_pejabat', $dinas->golongan_pejabat) }}"
                   placeholder="Contoh: IV/c"
                   class="{{ $field }}">
            @error('golongan_pejabat')
                <p class="{{ $galat }}">{{ $message }}</p>
            @enderror
        </x-admin.field>

        <x-admin.field label="NIP Pejabat (opsional)">
            <input type="text" name="nip_pejabat"
                   value="{{ old('nip_pejabat', $dinas->nip_pejabat) }}"
                   placeholder="NIP pejabat"
                   class="{{ $field }}">
            @error('nip_pejabat')
                <p class="{{ $galat }}">{{ $message }}</p>
            @enderror
        </x-admin.field>
    </div>
</div>

            <label class="flex items-center gap-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" name="status" value="1"
                       {{ old('status', $dinas->status) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 dark:bg-slate-950 text-blue-600 focus:ring-blue-500">
                Aktif (tampil di halaman publik &amp; pilihan pendaftaran)
            </label>

            @error('status') <p class="{{ $galat }}">{{ $message }}</p> @enderror

            <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-500 text-white font-bold text-sm rounded-xl shadow-sm dark:shadow-none transition">
                Simpan Perubahan
            </button>
        </form>
    </div>

@endsection