{{--
    Field form Dinas, dipakai bersama oleh halaman tambah (index) dan edit.
    Pemanggil menyediakan <form>, @csrf, dan @method. Kirim $dinas hanya saat edit.
--}}
@php
    $d     = $dinas ?? null;
    $label = 'block mb-1.5 text-sm font-semibold text-slate-800 dark:text-slate-200';
    $field = 'w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950/60
              text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500
              focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/20 transition';
    $galat = 'mt-1 text-xs font-semibold text-rose-600 dark:text-rose-400';

    $umum = [
        ['nama_dinas', 'Nama Dinas',    'text',  'Nama lengkap dinas'],
        ['alamat',     'Alamat Kantor', 'text',  'Alamat lengkap kantor (tampil di kop surat)'],
        ['telepon',    'Telepon Kantor','text',  'Nomor telepon kantor'],
        ['email',      'Email Dinas',   'email', 'email@ponorogo.go.id'],
        ['website',    'Website Dinas', 'text',  'kominfo.ponorogo.go.id'],
    ];

    $pejabat = [
        ['nama_pejabat',     'Nama Pejabat',     'text', 'Nama lengkap beserta gelar'],
        ['jabatan_pejabat',  'Jabatan Pejabat',  'text', 'Contoh: Kepala Dinas Komunikasi Informatika dan Statistik'],
        ['pangkat_pejabat',  'Pangkat',          'text', 'Contoh: Pembina Utama Muda'],
        ['golongan_pejabat', 'Golongan',         'text', 'Contoh: IV/c'],
        ['nip_pejabat',      'NIP Pejabat',      'text', 'NIP pejabat'],
    ];
@endphp

<div class="space-y-5">

    {{-- Data dinas & kop surat --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        @foreach ($umum as [$k, $nama, $tipe, $ph])
            <div>
                <label for="{{ $k }}" class="{{ $label }}">
                    {{ $nama }}
                    @if ($k !== 'nama_dinas')<span class="font-normal text-slate-400 dark:text-slate-500">(opsional)</span>@endif
                </label>
                <input id="{{ $k }}" type="{{ $tipe }}" name="{{ $k }}" value="{{ old($k, $d?->{$k} ?? '') }}"
                       placeholder="{{ $ph }}" @required($k === 'nama_dinas')
                       class="{{ $field }} {{ $errors->has($k) ? '!border-rose-500' : '' }}">
                @error($k) <p class="{{ $galat }}">{{ $message }}</p> @enderror
            </div>
        @endforeach
    </div>

    <div>
        <label for="deskripsi" class="{{ $label }}">
            Deskripsi Dinas <span class="font-normal text-slate-400 dark:text-slate-500">(opsional)</span>
        </label>
        <textarea id="deskripsi" name="deskripsi" rows="2" placeholder="Deskripsi singkat dinas"
                  class="{{ str_replace('h-10', 'py-2.5', $field) }}">{{ old('deskripsi', $d?->deskripsi ?? '') }}</textarea>
        @error('deskripsi') <p class="{{ $galat }}">{{ $message }}</p> @enderror
    </div>

    {{-- Pejabat penandatangan --}}
    <div class="border-t border-slate-200 dark:border-slate-800 pt-5">
        <h4 class="text-base font-bold text-slate-900 dark:text-white">Pejabat Penandatangan</h4>
        <p class="mt-0.5 mb-4 text-sm text-slate-500 dark:text-slate-400">
            Dipakai sebagai nilai awal identitas penandatangan pada draf surat balasan.
            Data masuk otomatis ke template surat dan masih dapat disesuaikan.
        </p>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @foreach ($pejabat as [$k, $nama, $tipe, $ph])
                <div>
                    <label for="{{ $k }}" class="{{ $label }}">
                        {{ $nama }} <span class="font-normal text-slate-400 dark:text-slate-500">(opsional)</span>
                    </label>
                    <input id="{{ $k }}" type="{{ $tipe }}" name="{{ $k }}" value="{{ old($k, $d?->{$k} ?? '') }}"
                           placeholder="{{ $ph }}" class="{{ $field }}">
                    @error($k) <p class="{{ $galat }}">{{ $message }}</p> @enderror
                </div>
            @endforeach
        </div>
    </div>

    {{-- Status + simpan --}}
    <div class="flex flex-col gap-4 border-t border-slate-200 dark:border-slate-800 pt-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <label class="inline-flex items-center gap-2.5 text-sm font-medium text-slate-700 dark:text-slate-300">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" name="status" value="1" {{ old('status', $d ? $d->status : true) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-slate-300 dark:border-slate-600 dark:bg-slate-950 text-blue-600 focus:ring-blue-500">
                Aktif (tampil di halaman publik &amp; pilihan pendaftaran)
            </label>
            @error('status') <p class="{{ $galat }}">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="h-10 shrink-0 rounded-lg bg-blue-600 px-6 text-sm font-bold text-white transition
                       hover:bg-blue-700 dark:hover:bg-blue-500">
            {{ $d ? 'Simpan Perubahan' : 'Simpan Dinas' }}
        </button>
    </div>
</div>