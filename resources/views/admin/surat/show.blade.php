@extends('admin.layout.app')

@section('page-title', 'Surat Balasan Magang')

@section('content')
@php
    $terbit = $surat->sudahTerbit();
    $adaDraf = filled($surat->file_draf);
    $versi = $surat->updated_at?->timestamp;

    $kelasInput = 'w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-slate-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition disabled:opacity-60';
@endphp

    @if(session('success'))
        <x-alert type="success"> {{ session('success') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
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

    {{-- RINGKASAN PENDAFTAR --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="min-w-0">
            <a href="{{ route('admin.verifikasi.index') }}" class="text-xs font-bold text-slate-500 hover:text-cyan-700 transition">Kembali ke Verifikasi</a>
            <h3 class="mt-1 text-lg font-extrabold text-slate-900 truncate">{{ $pendaftaran->nama_lengkap }}</h3>
            <p class="text-sm text-slate-500">
                {{ $pendaftaran->nim_nisn }} · {{ $pendaftaran->instansi }} · {{ $pendaftaran->jurusan }}
            </p>
            <p class="mt-1 text-xs text-slate-400">
                {{ $pendaftaran->dinas_tujuan }}@if($pendaftaran->divisi) · {{ $pendaftaran->divisi }}@endif
                @if($pendaftaran->tanggal_mulai && $pendaftaran->tanggal_selesai)
                    · {{ \Carbon\Carbon::parse($pendaftaran->tanggal_mulai)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->format('d/m/Y') }}
                @endif
            </p>
        </div>

        <div class="flex items-center gap-3 shrink-0">
            @if($pendaftaran->surat_pengantar)
                <a href="{{ asset('storage/' . $pendaftaran->surat_pengantar) }}" target="_blank" rel="noopener"
                   class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                    Lihat Surat Pengantar
                </a>
            @endif
            <span class="px-3 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wide
                {{ $terbit ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                {{ $terbit ? 'Surat Terbit' : 'Draf' }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6 items-start">

        {{-- ========== FORM DATA SURAT ========== --}}
        <form action="{{ route('admin.surat.update', $pendaftaran->id) }}" method="POST"
              class="xl:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <h3 class="font-extrabold text-slate-900">Data Surat</h3>
                <p class="mt-1 text-xs text-slate-500">
                    Periksa dan koreksi isian di bawah. Setiap disimpan, draf PDF dibuat ulang otomatis.
                </p>
            </div>

            <fieldset @disabled($terbit) class="space-y-6">

                {{-- Surat balasan --}}
                <div class="space-y-4">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Surat Balasan</p>

                    <x-admin.field label="Nomor Surat">
                        <input type="text" name="nomor_surat" class="{{ $kelasInput }}"
                               placeholder="Contoh: 400.14.5.4/123/405.20/2026"
                               value="{{ old('nomor_surat', $surat->nomor_surat) }}">
                    </x-admin.field>

                    <div class="grid grid-cols-2 gap-4">
                        <x-admin.field label="Tanggal Surat">
                            <input type="date" name="tanggal_surat" required class="{{ $kelasInput }}"
                                   value="{{ old('tanggal_surat', $surat->tanggal_surat?->format('Y-m-d')) }}">
                        </x-admin.field>

                        <x-admin.field label="Jenis Kegiatan">
                            <select name="jenis_kegiatan" required class="{{ $kelasInput }}">
                                @foreach(\App\Models\SuratBalasan::JENIS_KEGIATAN as $jenis)
                                    <option value="{{ $jenis }}" @selected(old('jenis_kegiatan', $surat->jenis_kegiatan) === $jenis)>{{ $jenis }}</option>
                                @endforeach
                            </select>
                        </x-admin.field>
                    </div>
                </div>

                {{-- Tujuan surat --}}
                <div class="space-y-4">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Ditujukan Kepada</p>

                    <x-admin.field label="Nama Kampus / Sekolah">
                        <input type="text" name="nama_instansi" required class="{{ $kelasInput }}"
                               value="{{ old('nama_instansi', $surat->nama_instansi) }}">
                    </x-admin.field>

                    <x-admin.field label="Kota Kampus / Sekolah">
                        <input type="text" name="kota_instansi" required class="{{ $kelasInput }}"
                               placeholder="Contoh: Surabaya"
                               value="{{ old('kota_instansi', $surat->kota_instansi) }}">
                    </x-admin.field>
                </div>

                {{-- Surat permohonan --}}
                <div class="space-y-4">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Surat Permohonan dari Kampus / Sekolah</p>
                        <p class="mt-1 text-xs text-slate-400">Isi dari surat pengantar yang diunggah peserta. Kosongkan nomor/tanggal jika tidak ada.</p>
                    </div>

                    <x-admin.field label="Jabatan Penandatangan Surat Permohonan">
                        <input type="text" name="jabatan_pemohon" class="{{ $kelasInput }}"
                               placeholder="Contoh: Direktur Pendidikan dan Transformasi Pendidikan"
                               value="{{ old('jabatan_pemohon', $surat->jabatan_pemohon) }}">
                    </x-admin.field>

                    <div class="grid grid-cols-2 gap-4">
                        <x-admin.field label="Nomor Surat Permohonan">
                            <input type="text" name="nomor_surat_pemohon" class="{{ $kelasInput }}"
                                   value="{{ old('nomor_surat_pemohon', $surat->nomor_surat_pemohon) }}">
                        </x-admin.field>

                        <x-admin.field label="Tanggal Surat Permohonan">
                            <input type="date" name="tanggal_surat_pemohon" class="{{ $kelasInput }}"
                                   value="{{ old('tanggal_surat_pemohon', $surat->tanggal_surat_pemohon?->format('Y-m-d')) }}">
                        </x-admin.field>
                    </div>
                </div>

                {{-- Penandatangan --}}
                <div class="space-y-4">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Penandatangan</p>

                    <x-admin.field label="Nama (beserta gelar)">
                        <input type="text" name="nama_penandatangan" required class="{{ $kelasInput }}"
                               value="{{ old('nama_penandatangan', $surat->nama_penandatangan) }}">
                    </x-admin.field>

                    <x-admin.field label="Jabatan">
                        <input type="text" name="jabatan_penandatangan" required class="{{ $kelasInput }}"
                               value="{{ old('jabatan_penandatangan', $surat->jabatan_penandatangan) }}">
                    </x-admin.field>

                    <div class="grid grid-cols-2 gap-4">
                        <x-admin.field label="Pangkat / Golongan">
                            <input type="text" name="pangkat_penandatangan" class="{{ $kelasInput }}"
                                   value="{{ old('pangkat_penandatangan', $surat->pangkat_penandatangan) }}">
                        </x-admin.field>

                        <x-admin.field label="NIP">
                            <input type="text" name="nip_penandatangan" class="{{ $kelasInput }}"
                                   value="{{ old('nip_penandatangan', $surat->nip_penandatangan) }}">
                        </x-admin.field>
                    </div>
                </div>
            </fieldset>

            @if($terbit)
                <p class="text-xs text-slate-500 bg-slate-50 border border-slate-200 rounded-xl p-3">
                    Surat sudah diterbitkan, jadi isiannya dikunci. Gunakan <strong>Tarik Penerbitan</strong>
                    di sebelah kanan jika perlu mengubahnya.
                </p>
            @else
                <button type="submit"
                        class="w-full px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-sm rounded-xl shadow-md transition">
                    Simpan &amp; Buat Ulang Draf
                </button>
                <p class="text-[11px] text-slate-400 text-center">Pembuatan PDF memerlukan beberapa detik.</p>
            @endif
        </form>

        {{-- ========== PRATINJAU + PENERBITAN ========== --}}
        <div class="xl:col-span-3 space-y-6">

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-3">
                    <h3 class="font-extrabold text-slate-900">{{ $terbit ? 'Surat Terbit' : 'Pratinjau Draf' }}</h3>

                    @if($adaDraf || $terbit)
                        <a href="{{ route('admin.surat.pratinjau', [$pendaftaran->id, 'unduh' => 1]) }}"
                           class="text-xs font-bold text-cyan-700 hover:text-cyan-800 transition">
                            {{ $terbit ? 'Unduh PDF Final' : 'Unduh Draf PDF' }}
                        </a>
                    @endif
                </div>

                @if($adaDraf || $terbit)
                    <iframe src="{{ route('admin.surat.pratinjau', $pendaftaran->id) }}?v={{ $versi }}"
                            title="Pratinjau surat balasan"
                            class="w-full h-[760px] bg-slate-100"></iframe>
                @else
                    <div class="h-72 flex flex-col items-center justify-center text-center px-6">
                        <p class="text-sm font-semibold text-slate-600">Draf belum dibuat</p>
                        <p class="mt-1 text-xs text-slate-400">Periksa data surat di sebelah kiri, lalu klik “Simpan &amp; Buat Ulang Draf”.</p>
                    </div>
                @endif
            </div>

            {{-- PENERBITAN --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
                @if($terbit)
                    <div>
                        <h3 class="font-extrabold text-slate-900">Surat sudah diterbitkan</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Diterbitkan {{ $surat->diterbitkan_pada?->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB
                            @if($surat->penerbit) oleh {{ $surat->penerbit->name }}@endif.
                            Peserta dapat mengunduhnya dari halaman status pendaftaran.
                        </p>
                    </div>

                    <form action="{{ route('admin.surat.tarik', $pendaftaran->id) }}" method="POST"
                          onsubmit="return confirm('Tarik penerbitan? File final dihapus dan peserta tidak bisa lagi mengunduh surat ini.')">
                        @csrf
                        <button type="submit"
                                class="px-5 py-2.5 rounded-xl border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 text-sm font-bold transition">
                            Tarik Penerbitan
                        </button>
                    </form>
                @else
                    <div>
                        <h3 class="font-extrabold text-slate-900">Terbitkan Surat</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            @if($wajibTtd)
                                Unduh draf, tandatangani secara elektronik (mis. TTE BSrE), lalu unggah PDF yang sudah ditandatangani.
                                File itulah yang diunduh peserta.
                            @else
                                Setelah draf diperiksa, surat dapat diterbitkan. Anda juga dapat mengunggah PDF yang sudah ditandatangani
                                untuk menggantikan PDF buatan sistem.
                            @endif
                        </p>
                    </div>

                    <ul class="text-xs space-y-1.5">
                        <li class="{{ filled($surat->nomor_surat) ? 'text-emerald-700' : 'text-slate-400' }}">
                            {{ filled($surat->nomor_surat) ? '✓' : '○' }} Nomor surat terisi
                        </li>
                        <li class="{{ $adaDraf ? 'text-emerald-700' : 'text-slate-400' }}">
                            {{ $adaDraf ? '✓' : '○' }} Draf PDF sudah dibuat dan diperiksa
                        </li>
                    </ul>

                    <form action="{{ route('admin.surat.terbitkan', $pendaftaran->id) }}" method="POST" enctype="multipart/form-data"
                          class="space-y-4"
                          onsubmit="return confirm('Terbitkan surat ini? Peserta akan dapat mengunduhnya.')">
                        @csrf

                        <x-admin.field :label="$wajibTtd ? 'PDF Bertanda Tangan (wajib)' : 'PDF Bertanda Tangan (opsional)'">
                            <input type="file" name="pdf_bertanda_tangan" accept="application/pdf" {{ $wajibTtd ? 'required' : '' }}
                                   class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:font-bold file:bg-cyan-50 file:text-cyan-700 border border-slate-200 rounded-xl bg-slate-50">
                        </x-admin.field>

                        <button type="submit" @disabled(! $adaDraf)
                                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-sm rounded-xl shadow-md transition">
                            Terbitkan Surat
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

@endsection