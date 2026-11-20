@extends('user.layout.app')

@section('title', 'Formulir Pendaftaran')


@push('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Kartu formulir - mode terang */
        .form-section {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background-color: #ffffff;
            padding: 20px;
            transition:
                background-color 0.2s ease,
                border-color 0.2s ease;
        }

        .form-section + .form-section {
            margin-top: 18px;
        }

        /* Kartu formulir - mode gelap */
        html.dark .form-section {
            background-color: #142544;
            border-color: #304467;
        }

        /* Teks pendukung dalam kartu */
        html.dark .form-section .text-slate-500 {
            color: #b7c5da;
        }

        html.dark .form-section .text-slate-400 {
            color: #a9bad2;
        }

        /* Pastikan teks utama tetap terbaca */
        html.dark .form-section .text-slate-800 {
            color: #f1f5f9;
        }
    </style>
@endpush

@section('content')
@php
    $isEdit = isset($pendaftaran);
    $user   = auth()->user();

    // Data pendaftaran diutamakan; kalau kolomnya kosong, ambil dari profil.
    $isi = fn (string $kolom) => $pendaftaran?->{$kolom} ?? $user->{$kolom};

    $namaLengkap = $isi('nama_lengkap') ?? $user->name;

    $tglMulaiVal = $isEdit && $pendaftaran->tanggal_mulai
        ? \Carbon\Carbon::parse($pendaftaran->tanggal_mulai)->format('Y-m-d')
        : null;

    $tglSelesaiVal = $isEdit && $pendaftaran->tanggal_selesai
        ? \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->format('Y-m-d')
        : null;
    
    $tglPengantarVal = $isEdit && $pendaftaran->tanggal_surat_pengantar
    ? \Carbon\Carbon::parse($pendaftaran->tanggal_surat_pengantar)->format('Y-m-d')
    : null;

    $dinasTerpilih = old('dinas_id', $pendaftaran?->dinas_id);

    // Draft: boleh simpan & hapus. Revisi: hanya boleh dikirim ulang.
    $isDraft           = $isEdit && $pendaftaran->status === 'draft';
    $kunciBackup = 'pendaftaran:' . $user->id . ':' . ($isEdit && ! $isDraft ? $pendaftaran->id : 'draft');
    $bisaSimpanDraft   = ! $isEdit || $isDraft;
    $hasSuratPengantar = $isEdit && filled($pendaftaran->surat_pengantar);

$tipe = old('tipe', $pendaftaran?->tipe ?? 'mandiri');

$anggotaTersimpan = $pendaftaran?->anggotas ?? collect();

$suratAnggota = $anggotaTersimpan->mapWithKeys(
    fn ($a) => [
        $a->id => $a->surat_pengantar
            ? asset('storage/' . $a->surat_pengantar)
            : null,
    ]
);

$anggotaAwal = collect(
    old(
        'anggota',
        $anggotaTersimpan
            ->map(fn ($a) => $a->only(\App\Models\AnggotaPendaftaran::KOLOM) + ['id' => $a->id])
            ->all()
    )
)
    ->map(fn ($a) => $a + [
        'surat' => $suratAnggota->get($a['id'] ?? 0),
    ])
    ->values()
    ->all();
@endphp
<div class="min-h-screen bg-slate-50 dark:bg-slate-900/50 py-8 sm:py-10 px-4 sm:px-6">
    <div class="max-w-4xl mx-auto">
        {{-- MAIN CARD --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
           
{{-- HEADER --}}
<div class="bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-600
            dark:from-[#172554] dark:via-[#1e3a6b] dark:to-[#164e63]
            px-6 sm:px-8 py-7">

    <div class="flex items-start gap-4">
        <div class="hidden sm:flex w-12 h-12 rounded-xl
                    bg-white/15 dark:bg-white/10
                    items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z" />
            </svg>
        </div>

        <div>
            <div class="inline-flex items-center px-2.5 py-1 rounded-full
                        bg-white/15 text-white text-[11px] font-semibold
                        uppercase tracking-wide mb-2">
                Portal Magang Resmi
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                Formulir Pendaftaran
            </h1>

            <p class="mt-1.5 text-sm text-blue-50
                      max-w-2xl leading-relaxed">
                Lengkapi data diri, instansi tujuan, periode magang,
                dan berkas persyaratan dengan benar.
            </p>
        </div>
    </div>
</div>


            {{-- FORM AREA --}}
            <div class="p-5 sm:p-7">

                {{-- ERROR DARI SERVER (setelah submit) --}}
                @if ($errors->any())
                    <div class="mb-5 p-4 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 rounded-xl text-rose-700 dark:text-rose-300 text-sm">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="font-semibold">Mohon periksa kembali formulir Anda.</p>
                                <ul class="mt-1 list-disc list-inside text-xs space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ERROR DARI JS (real-time, sebelum submit) --}}
                <div id="clientErrorBox" class="hidden mb-5 p-4 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 rounded-xl text-rose-700 dark:text-rose-300 text-sm">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="font-semibold">Mohon periksa kembali formulir Anda.</p>
                            <ul id="clientErrorList" class="mt-1 list-disc list-inside text-xs space-y-0.5"></ul>
                        </div>
                    </div>
                </div>

                <form
                    id="pendaftaranForm"
                    action="{{ $isEdit ? route('user.pendaftaran.update', $pendaftaran->id) : route('user.pendaftaran.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-5"
                    novalidate
                >
                    @csrf

                    @if($isEdit)
                        @method('PUT')
                    @endif

                    {{-- INSTANSI TUJUAN --}}
                    <div class="form-section">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-9 h-9 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 4h1m4 0h1" />
                                </svg>
                            </div>

                            <div>
                                <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Instansi Tujuan</h2>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Tentukan instansi dan bidang penempatan magang.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                          {{-- DINAS --}}
<x-form.select
    name="dinas_id"
    label="Instansi / Dinas Tujuan"
    id="dinasTujuan"
    required
    cari
    satuan="instansi"
    cari-placeholder="Ketik nama instansi atau pilih dari daftar..."
    onchange="gantiDinas()"
    hint="Pilih instansi tujuan magang. Ketik beberapa huruf untuk mencari."
>
    <option value="" disabled @selected(blank($dinasTerpilih))>
        -- Pilih Instansi --
    </option>

    @foreach ($dinases as $dinas)
        <option
            value="{{ $dinas->id }}"
            @selected((string) $dinasTerpilih === (string) $dinas->id)
        >
            {{ $dinas->nama_dinas }}
        </option>
    @endforeach
</x-form.select>

                            {{-- BIDANG (diisi JavaScript; sengaja tidak ada yang terpilih otomatis) --}}
                            <x-form.select
                                name="instansi_bidang_id"
                                label="Bidang Penempatan"
                                id="pilihanDivisi"
                                required
                                hint="Pilih bidang yang sesuai."
                            >
                                <option value="" selected>-- Pilih instansi terlebih dahulu --</option>
                            </x-form.select>
                        </div>
                    </div>

                    {{-- SECTION 1 --}}
<div class="form-section">
    <x-step-heading number="1" variant="indigo">
        Informasi Akademik & Data Diri
    </x-step-heading>

    <div class="mt-4 space-y-4">

        {{-- JENIS PENDAFTARAN --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach (\App\Models\PendaftaranMagang::TIPE as $nilai => $label)
                <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition
                              border-slate-200 dark:border-slate-600
                              has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50
                              dark:has-[:checked]:bg-indigo-500/10">
                    <input type="radio" name="tipe" value="{{ $nilai }}"
                           class="text-indigo-600 focus:ring-indigo-500"
                           @checked($tipe === $nilai)>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $label }}</span>
                </label>
            @endforeach
        </div>

<div id="petunjukKelompok" class="hidden rounded-xl border border-indigo-200 dark:border-indigo-500/25 bg-indigo-50/70 dark:bg-indigo-500/10 px-4 py-3">
    <p class="text-sm font-bold text-indigo-800 dark:text-indigo-200">Cara mengisi pendaftaran kelompok</p>
    <ol class="mt-1.5 list-decimal list-inside space-y-0.5 text-xs leading-relaxed text-indigo-900/80 dark:text-indigo-200/80">
        <li>Anda mengisi sebagai ketua. Data ketua diambil dari profil.</li>
        <li>Tambahkan setiap anggota. <strong>Setiap orang, termasuk Anda, memiliki nomor dan file surat pengantar sendiri.</strong></li>
        <li>Tanggal, pengirim, dan kota surat cukup diisi sekali di bagian 3 dan berlaku untuk semua.</li>
    </ol>
</div>

        <p id="labelKetua" class="hidden text-[11px] font-bold uppercase tracking-wide text-slate-400">
            Data Ketua Kelompok
            <span class="normal-case font-medium">(diambil dari profil Anda)</span>
        </p>

        <x-form.select name="kategori" label="Kategori Pendaftar" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach (\App\Models\PendaftaranMagang::KATEGORI as $nilai => $label)
                <option value="{{ $nilai }}" @selected(old('kategori', $isi('kategori')) === $nilai)>
                    {{ $label }}
                </option>
            @endforeach
        </x-form.select>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-form.input name="nama_lengkap" label="Nama Lengkap" required :value="$namaLengkap" />

    <x-form.input name="nim_nisn" label="NIM / NISN" required
                  placeholder="Contoh: 22050974001" :value="$isi('nim_nisn')" />

    <x-form.input name="instansi" label="Asal Instansi / Universitas" required
                  placeholder="Universitas Negeri Surabaya" :value="$isi('instansi')" />

    <x-form.input name="jurusan" label="Jurusan / Program Studi" required
                  placeholder="S1 Teknologi Pendidikan" :value="$isi('jurusan')" />

    <x-form.input name="no_hp" id="no_hp" label="No. WhatsApp / HP Aktif" required
                  placeholder="Contoh: 081234567890" inputmode="numeric" :value="$isi('no_hp')"
                  oninput="this.value=this.value.replace(/[^0-9]/g,'');" />

    <x-form.input name="nomor_surat_pengantar" label="Nomor Surat Pengantar" required
                  placeholder="Contoh: B/20069/UN38.I.1/TU.00/2026"
                  :value="old('nomor_surat_pengantar', $pendaftaran?->nomor_surat_pengantar)" />

    <div class="md:col-span-2">
        <x-form.file name="surat_pengantar" label="Surat Pengantar" :required="! $hasSuratPengantar" accept=".pdf"
                     :hint="$hasSuratPengantar
                         ? 'File saat ini: ' . basename($pendaftaran->surat_pengantar) . '. Pilih file baru hanya bila ingin menggantinya.'
                         : 'PDF, maksimal 2MB. Surat pengantar atas nama Anda.'" />

        <a id="lihatSuratKetua" target="_blank" rel="noopener"
           href="{{ $hasSuratPengantar ? asset('storage/' . $pendaftaran->surat_pengantar) : '#' }}"
           class="{{ $hasSuratPengantar ? '' : 'hidden' }} mt-2 inline-flex text-xs font-semibold text-blue-600 hover:underline dark:text-cyan-400">
            Lihat surat yang sudah diunggah
        </a>
    </div>
</div>
        {{-- ANGGOTA KELOMPOK (hanya tampil jika berkelompok) --}}
        <div id="blokAnggota" class="hidden space-y-3 pt-4 border-t border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between gap-3">
                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400">
                    Anggota Kelompok <span id="jumlahAnggota"></span>
                </p>
                <button type="button" id="btnTambahAnggota" onclick="bukaEditor()"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition
                               bg-indigo-50 text-indigo-700 hover:bg-indigo-100
                               dark:bg-indigo-500/10 dark:text-indigo-300 dark:hover:bg-indigo-500/20
                               disabled:opacity-50 disabled:cursor-not-allowed">
                    + Tambah Anggota
                </button>
            </div>

            <ul id="daftarAnggota" class="space-y-2"></ul>
            <div id="hiddenAnggota"></div>
            <div id="berkasAnggota" class="hidden"></div>

            {{-- Editor (dipakai untuk tambah & edit). Input sengaja di luar form (atribut form tidak valid)
                 supaya tidak ikut terkirim; yang terkirim hanya hidden input anggota[i][kolom]. --}}
            <div id="editorAnggota" class="hidden rounded-xl border border-slate-200 dark:border-slate-600 p-4 space-y-3">
                <h4 id="judulEditor" class="text-sm font-bold text-slate-800 dark:text-slate-100"></h4>

             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach ([
    'nama_lengkap'          => 'Nama Lengkap',
    'nim_nisn'              => 'NIM / NISN',
    'instansi'              => 'Asal Instansi / Universitas',
    'jurusan'               => 'Jurusan / Program Studi',
    'no_hp'                 => 'No. WhatsApp / HP Aktif',
    'nomor_surat_pengantar' => 'Nomor Surat Pengantar',
] as $kolom => $label)
    <x-form.input :name="'ang_' . $kolom" :label="$label" required form="editorAnggota"
                  :inputmode="$kolom === 'no_hp' ? 'numeric' : null"
                  :placeholder="$kolom === 'nomor_surat_pengantar' ? 'Contoh: B/20069/UN38.I.1/TU.00/2026' : null" />
@endforeach

<div class="md:col-span-2">
    <x-form.file name="ang_surat_pengantar" label="Surat Pengantar Anggota" required accept=".pdf" form="editorAnggota"
                 hint="PDF, maksimal 2MB. Tanggal, pengirim, dan kota surat mengikuti data surat ketua di bagian 3." />
    <p id="infoSuratEditor" class="hidden mt-2 text-xs text-slate-500 dark:text-slate-400"></p>
</div>
</div>
                <p class="text-xs text-slate-500 dark:text-slate-400">Kategori anggota mengikuti ketua.</p>
                <p id="errorEditor" class="hidden text-xs font-medium text-rose-600 dark:text-rose-400"></p>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="tutupEditor()"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100
                                   dark:text-slate-200 dark:hover:bg-slate-700 transition">Batal</button>
                    <button type="button" onclick="simpanAnggota()"
                            class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition">
                        Simpan Anggota
                    </button>
                </div>
            </div>

            {{-- Detail anggota --}}
            <dialog id="dialogAnggota"
        class="m-auto w-[min(92vw,28rem)] overflow-hidden rounded-2xl border border-slate-200 p-0 shadow-2xl
               bg-white text-slate-800 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100
               backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm">

    <div class="flex items-center gap-3 border-b border-slate-100 bg-slate-50 px-5 py-4 dark:border-slate-700 dark:bg-slate-900/40">
        <span id="inisialDetail"
              class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300"></span>
        <div class="min-w-0">
            <p id="namaDetail" class="truncate text-base font-bold"></p>
            <p class="text-xs text-slate-500 dark:text-slate-400">Anggota kelompok</p>
        </div>
    </div>

    <dl id="isiDetailAnggota" class="grid grid-cols-2 gap-x-5 gap-y-3 px-5 py-4 text-sm"></dl>

    <div class="flex items-center justify-between gap-3 border-t border-slate-100 px-5 py-4 dark:border-slate-700">
        <a id="lihatSuratDetail" href="#" target="_blank" rel="noopener"
           class="hidden rounded-lg border border-blue-200 bg-blue-50 px-3.5 py-2 text-xs font-semibold text-blue-700 transition
                  hover:bg-blue-100 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300 dark:hover:bg-blue-500/20">
            Lihat Surat Pengantar
        </a>
        <span id="tanpaSuratDetail" class="hidden text-xs text-amber-700 dark:text-amber-300">Surat pengantar belum diunggah</span>

        <button type="button" onclick="this.closest('dialog').close()"
                class="ml-auto rounded-lg bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700 transition
                       hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-100 dark:hover:bg-slate-600">
            Tutup
        </button>
    </div>
</dialog>
        </div>

    </div>
</div>

                    {{-- SECTION 2 --}}
                    <div class="form-section">
                        <x-step-heading number="2" variant="indigo">
                            Periode Pelaksanaan Magang
                        </x-step-heading>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-form.input
                                type="date"
                                name="tanggal_mulai"
                                id="tanggal_mulai"
                                label="Tanggal Mulai Magang"
                                required
                                min="{{ date('Y-m-d') }}"
                                :value="$tglMulaiVal"
                                onchange="document.getElementById('tanggal_selesai').min=this.value;"
                            />
                            <x-form.input
                                type="date"
                                name="tanggal_selesai"
                                id="tanggal_selesai"
                                label="Tanggal Selesai Magang"
                                required
                                :value="$tglSelesaiVal"
                            />
                        </div>
                    </div>

                    {{-- SECTION 3 --}}
<div class="form-section">
    <x-step-heading number="3" variant="indigo">
        Data Surat Pengantar &amp; Proposal
    </x-step-heading>

    <p class="mt-3 mb-4 sm:ml-10 text-xs leading-relaxed text-slate-500 dark:text-slate-400">
        Isi sesuai surat pengantar dari kampus/sekolah. Pada pendaftaran berkelompok, data ini dipakai bersama oleh seluruh anggota.
        Format file wajib <strong class="text-slate-700 dark:text-slate-200">PDF</strong>, maksimal
        <strong class="text-slate-700 dark:text-slate-200">2MB</strong> per file.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-form.input type="date" name="tanggal_surat_pengantar" id="tanggal_surat_pengantar"
                      label="Tanggal Surat Pengantar" required max="{{ date('Y-m-d') }}"
                      :value="old('tanggal_surat_pengantar', $tglPengantarVal)" />

        <x-form.input name="jabatan_penerima" label="Pengirim Surat" required
                      placeholder="Contoh: Direktur Pendidikan dan Transformasi Pendidikan"
                      hint="Jabatan pejabat kampus/sekolah yang menandatangani surat pengantar; surat balasan ditujukan kepadanya."
                      :value="old('jabatan_penerima', $pendaftaran?->jabatan_penerima)" />

        <x-form.input name="kota_tujuan" label="Kota Kampus / Sekolah" required placeholder="Contoh: Surabaya"
                      :value="old('kota_tujuan', $pendaftaran?->kota_tujuan)" />

        <x-form.file name="proposal" label="Proposal Magang" accept=".pdf"
                     :hint="$isEdit && filled($pendaftaran->proposal) ? 'File saat ini: ' . basename($pendaftaran->proposal) : 'Opsional.'" />
    </div>
</div>

                        
{{-- DATA SURAT PENGANTAR (dipakai mengisi surat balasan) --}}
<div class="mb-5">
    <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400 mb-3">
        Data Surat Pengantar
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-form.input
            name="nomor_surat_pengantar"
            label="Nomor Surat Pengantar"
            required
            placeholder="Contoh: B/20069/UN38.I.1/TU.00/2026"
            :value="old('nomor_surat_pengantar', $pendaftaran?->nomor_surat_pengantar)"
        />

        <x-form.input
            type="date"
            name="tanggal_surat_pengantar"
            id="tanggal_surat_pengantar"
            label="Tanggal Surat Pengantar"
            required
            max="{{ date('Y-m-d') }}"
            :value="old('tanggal_surat_pengantar', $tglPengantarVal)"
        />

        <x-form.input
            name="jabatan_penerima"
            label="Pengirim Surat"
            required
            placeholder="Contoh: Direktur Pendidikan dan Transformasi Pendidikan"
            hint="Jabatan pejabat kampus/sekolah yang menandatangani surat pengantar; surat balasan ditujukan kepadanya."
            :value="old('jabatan_penerima', $pendaftaran?->jabatan_penerima)"
        />

        <x-form.input
            name="kota_tujuan"
            label="Kota Kampus / Sekolah"
            required
            placeholder="Contoh: Surabaya"
            :value="old('kota_tujuan', $pendaftaran?->kota_tujuan)"
        />
    </div>
</div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-form.file
                                name="surat_pengantar"
                                label="Surat Pengantar Instansi"
                                :required="! $hasSuratPengantar"
                                accept=".pdf"
                                :hint="$hasSuratPengantar ? 'File saat ini: '.basename($pendaftaran->surat_pengantar) : null"
                            />
                            <x-form.file
                                name="proposal"
                                label="Proposal Magang"
                                accept=".pdf"
                                :hint="$isEdit && filled($pendaftaran->proposal) ? 'File saat ini: '.basename($pendaftaran->proposal) : null"
                            />
                        </div>
                    </div>


{{-- ACTION --}}
<div class="pt-5 border-t border-slate-200 dark:border-slate-700">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        {{-- KEMBALI --}}
        <a
            href="{{ route('user.dashboard') }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3
                   text-sm font-semibold rounded-xl
                   text-slate-600 bg-transparent
                   hover:text-slate-900 hover:bg-slate-100
                   dark:text-slate-200 dark:bg-slate-800/60
                   dark:hover:text-white dark:hover:bg-slate-700
                   focus-visible:outline-none focus-visible:ring-2
                   focus-visible:ring-blue-500 dark:focus-visible:ring-cyan-500
                   transition-colors duration-200"
        >
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Dashboard
        </a>

        {{-- KELOMPOK TOMBOL AKSI --}}
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">

            @if($isDraft)
                {{-- HAPUS DRAFT --}}
                <button
                    type="button"
                    onclick="hapusDraft()"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3
                           bg-white text-rose-600 border border-rose-200
                           hover:bg-rose-50 hover:border-rose-300
                           dark:bg-slate-800 dark:text-rose-400 dark:border-rose-500/30
                           dark:hover:bg-rose-500/10 dark:hover:border-rose-500/50
                           rounded-xl font-semibold transition-colors duration-200 text-sm
                           focus-visible:outline-none focus-visible:ring-2
                           focus-visible:ring-rose-500"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    </svg>
                    Hapus Draft
                </button>
            @endif

            @if($bisaSimpanDraft)
                {{-- SIMPAN DRAFT --}}
                <button
                    type="button"
                    id="btnSimpanDraft"
                    onclick="simpanDraft()"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3
                           bg-white text-slate-700 border border-slate-300
                           hover:bg-indigo-50 hover:border-indigo-300
                           dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600
                           dark:hover:bg-slate-700 dark:hover:text-white dark:hover:border-cyan-500/50
                           rounded-xl font-semibold transition-colors duration-200 text-sm
                           focus-visible:outline-none focus-visible:ring-2
                           focus-visible:ring-blue-500 dark:focus-visible:ring-cyan-500"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    <span id="textSimpanDraft">Simpan Draft</span>
                </button>
            @endif

            {{-- KIRIM --}}
            <button
                type="button"
                onclick="konfirmasiKirim()"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3
                       bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm
                       rounded-xl shadow-sm hover:shadow-md transition
                       focus-visible:outline-none focus-visible:ring-2
                       focus-visible:ring-indigo-500 focus-visible:ring-offset-2
                       dark:focus-visible:ring-offset-slate-900"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 12h14m-6-6l6 6-6 6" />
                </svg>
                {{ $isEdit && $pendaftaran->status === 'revisi' ? 'Kirim Ulang' : 'Kirim Pendaftaran' }}
            </button>

        </div>
    </div>
</div>

                </form>

                @if($isDraft)
                    <form id="hapusDraftForm"
                          action="{{ route('user.pendaftaran.destroy', $pendaftaran->id) }}"
                          method="POST"
                          class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </div>

        {{-- FOOTER INFO --}}
        <div class="mt-4 text-center">
            @if($bisaSimpanDraft)
                <p id="autosaveStatus" class="text-xs text-slate-400 mb-1"></p>
            @endif
            <p class="text-xs text-slate-400">
                Pastikan seluruh data dan dokumen yang Anda masukkan sudah benar sebelum dikirim.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ==========================================
    // KONTEKS FORM
    // ==========================================
    const URL_BIDANG    = @json(url('user/pendaftaran/bidang'));
    const URL_DRAFT     = @json(route('user.pendaftaran.draft'));
    const URL_DASHBOARD = @json(route('user.dashboard'));
    const AUTOSAVE_AKTIF = @json($bisaSimpanDraft);   // revisi tidak boleh membuat draft baru
    let hasSuratPengantar = @json($hasSuratPengantar);
    const KUNCI_BACKUP = @json($kunciBackup);
    // ==========================================
    // BIDANG INSTANSI
    // ==========================================
    async function updateBidang(terpilih = null) {
        const dinasId = document.getElementById('dinasTujuan').value;
        const select  = document.getElementById('pilihanDivisi');
        const isi = teks => { select.innerHTML = `<option value="" selected>${teks}</option>`; };

        if (!dinasId) return isi('-- Pilih instansi terlebih dahulu --');
        isi('Memuat bidang...');

        try {
            const res = await fetch(`${URL_BIDANG}/${dinasId}`, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('Gagal mengambil data bidang.');
            const data = await res.json();

            // Dinas diganti lagi saat menunggu respons -> abaikan hasil lama.
            if (document.getElementById('dinasTujuan').value !== dinasId) return;

            if (!data.length) return isi('Tidak ada bidang tersedia');

            // Placeholder tetap terpilih; bidang baru dipilih hanya jika ada nilai tersimpan.
            isi('-- Pilih Bidang / Divisi Tujuan --');
            data.forEach(b => select.add(
                new Option(b.nama_bidang, b.id, false, terpilih !== null && String(b.id) === String(terpilih))
            ));
        } catch (error) {
            console.error(error);
            isi('Gagal memuat bidang');
        }
    }

    // Dipanggil saat user mengganti dinas.
    function gantiDinas() {
        updateBidang().then(() => {
            document.getElementById('pilihanDivisi')
                .dispatchEvent(new Event('change', { bubbles: true }));
        });
    }
// ==========================================
// ANGGOTA KELOMPOK
// ==========================================
const KOLOM_ANGGOTA = ['nama_lengkap', 'nim_nisn', 'instansi', 'jurusan', 'no_hp', 'nomor_surat_pengantar'];
const LABEL_ANGGOTA = {
    nama_lengkap: 'Nama Lengkap', nim_nisn: 'NIM / NISN', instansi: 'Asal Instansi',
    jurusan: 'Jurusan / Program Studi', no_hp: 'No. WhatsApp / HP', nomor_surat_pengantar: 'Nomor Surat Pengantar',
};
const MAKS_ANGGOTA = @json(\App\Models\PendaftaranMagang::MAKS_ANGGOTA);

const esc = s => String(s ?? '').replace(/[&<>"']/g, c =>
    ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
const tipeDipilih = () => document.querySelector('input[name="tipe"]:checked')?.value ?? 'mandiri';
const ed  = k => document.querySelector(`[name="ang_${k}"]`);
const $id = id => document.getElementById(id);

const buatUid   = () => (crypto.randomUUID?.() ?? Math.random().toString(36).slice(2)).slice(0, 36);
const normalisasi = a => { a.uid ??= a.id ? 'id' + a.id : buatUid(); a.surat ??= null; return a; };

let anggota = @json($anggotaAwal).map(normalisasi);
const berkasAnggota = new Map();   // uid -> <input type="file"> berisi PDF yang dipilih tetapi belum terunggah
let editIndex = null;

const suratAda = a => Boolean(a.surat) || berkasAnggota.has(a.uid);

/** URL untuk membuka surat: pratinjau file yang baru dipilih, atau PDF yang sudah tersimpan. */
function urlSurat(a) {
    const berkas = berkasAnggota.get(a.uid)?.files[0];
    return berkas ? URL.createObjectURL(berkas) : a.surat;
}

function renderAnggota() {
    const kelompok = tipeDipilih() === 'kelompok';

    $id('daftarAnggota').innerHTML = anggota.map((a, i) => `
        <li class="flex items-center gap-3 rounded-xl border border-slate-200 dark:border-slate-600 px-3 py-2.5">
            <span class="w-8 h-8 shrink-0 rounded-full flex items-center justify-center text-xs font-bold
                         bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300">${i + 1}</span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 break-words">${esc(a.nama_lengkap)}</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 break-words">${esc(a.nim_nisn)} · Surat No. ${esc(a.nomor_surat_pengantar)}</p>
            </div>
            <span class="shrink-0 rounded-full px-2 py-0.5 text-[11px] font-semibold ${suratAda(a)
                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300'
                : 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300'}">
                ${suratAda(a) ? 'Surat terlampir' : 'Belum ada surat'}
            </span>
            <details class="relative">
                <summary aria-label="Menu anggota"
                         class="list-none cursor-pointer px-2 py-1 rounded-lg text-lg leading-none
                                text-slate-500 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700">⋮</summary>
                <div class="absolute right-0 z-10 mt-1 w-36 rounded-xl border border-slate-200 dark:border-slate-600
                            bg-white dark:bg-slate-800 shadow-lg py-1 text-sm">
                    ${[['edit', 'Edit', ''], ['lihat', 'Lihat detail', ''], ['hapus', 'Hapus', 'text-rose-600 dark:text-rose-400']]
                        .map(([aksi, teks, warna]) => `
                        <button type="button" data-aksi="${aksi}" data-i="${i}"
                                class="block w-full text-left px-3 py-1.5 hover:bg-slate-100 dark:hover:bg-slate-700 ${warna}">${teks}</button>`).join('')}
                </div>
            </details>
        </li>`).join('');

    // Field tersembunyi hanya dikirim bila berkelompok; data tetap di memori jika user kembali memilih "Berkelompok".
    $id('hiddenAnggota').innerHTML = kelompok
        ? anggota.map((a, i) => [
              ...KOLOM_ANGGOTA.map(k => [k, a[k]]),
              ['uid', a.uid],
              ...(a.id ? [['id', a.id]] : []),
          ].map(([k, v]) => `<input type="hidden" name="anggota[${i}][${k}]" value="${esc(v)}">`).join('')).join('')
        : '';

    // Kolom berkas: nama mengikuti urutan terbaru; kolom milik anggota yang sudah dihapus dibuang.
    const kontainer = $id('berkasAnggota');
    const aktif = new Set(anggota.map(a => a.uid));

    [...berkasAnggota].forEach(([uid, el]) => {
        if (!aktif.has(uid)) { el.remove(); berkasAnggota.delete(uid); }
    });

    anggota.forEach((a, i) => {
        const el = berkasAnggota.get(a.uid);
        if (!el) return;

        el.name = `anggota[${i}][surat_pengantar]`;
        el.disabled = !kelompok;   // mode mandiri: tidak ikut terkirim
        if (el.parentElement !== kontainer) kontainer.append(el);
    });

    $id('jumlahAnggota').textContent = anggota.length ? `(${anggota.length})` : '';
    $id('btnTambahAnggota').disabled = anggota.length >= MAKS_ANGGOTA;
}

function terapkanTipe() {
    const kelompok = tipeDipilih() === 'kelompok';

    $id('blokAnggota').classList.toggle('hidden', !kelompok);
    $id('labelKetua').classList.toggle('hidden', !kelompok);
    $id('petunjukKelompok').classList.toggle('hidden', !kelompok);

    if (!kelompok) tutupEditor();
    renderAnggota();
}

function infoSuratEditor(a) {
    const info = $id('infoSuratEditor');
    const url  = a ? urlSurat(a) : null;

    info.innerHTML = url
        ? `File saat ini: <a href="${esc(url)}" target="_blank" rel="noopener" class="font-semibold text-blue-600 hover:underline dark:text-cyan-400">Lihat surat</a>. Pilih file baru hanya bila ingin menggantinya.`
        : '';
    info.classList.toggle('hidden', !url);
}

function tampilkanErrorEditor(pesan) {
    $id('errorEditor').textContent = pesan;
    $id('errorEditor').classList.remove('hidden');
}

function bukaEditor(i = null) {
    editIndex = i;
    const a = i === null ? { instansi: fieldEl('instansi')?.value ?? '' } : anggota[i];

    KOLOM_ANGGOTA.forEach(k => { ed(k).value = a[k] ?? ''; });
    ed('surat_pengantar').value = '';
    infoSuratEditor(i === null ? null : a);

    $id('judulEditor').textContent = i === null ? 'Tambah Anggota' : `Edit Anggota ${i + 1}`;
    $id('errorEditor').classList.add('hidden');
    $id('editorAnggota').classList.remove('hidden');
    ed('nama_lengkap').focus();
}

function tutupEditor() {
    editIndex = null;
    $id('editorAnggota').classList.add('hidden');
}

function cekAnggota(a) {
    for (const k of KOLOM_ANGGOTA) {
        const pesan = validators[k](a[k]);
        if (pesan) return pesan;
    }

    const lama   = editIndex === null ? null : anggota[editIndex];
    const berkas = ed('surat_pengantar').files[0];

    if (!berkas && !(lama && suratAda(lama))) return 'Surat pengantar anggota wajib diunggah (PDF).';

    if (berkas) {
        const pesan = validateFile(ed('surat_pengantar'), false);
        if (pesan) return pesan;
    }

    const nimLain = [fieldEl('nim_nisn')?.value.trim(), ...anggota.filter((_, j) => j !== editIndex).map(x => x.nim_nisn)];
    return nimLain.includes(a.nim_nisn) ? 'NIM / NISN sudah dipakai ketua atau anggota lain.' : null;
}

function simpanAnggota() {
    const a = Object.fromEntries(KOLOM_ANGGOTA.map(k => [k, ed(k).value.trim()]));
    const pesan = cekAnggota(a);

    if (pesan) return tampilkanErrorEditor(pesan);

    const item = normalisasi({ ...(editIndex === null ? {} : anggota[editIndex]), ...a });   // uid, id, surat dipertahankan

    // PDF yang dipilih: kolomnya dipindah ke penampung tersembunyi, editor mendapat kolom kosong yang baru.
    const pilihan = ed('surat_pengantar');
    if (pilihan.files.length) {
        const pengganti = pilihan.cloneNode();
        pilihan.replaceWith(pengganti);
        ['id', 'form', 'required'].forEach(n => pilihan.removeAttribute(n));
        pilihan.classList.add('hidden');

        berkasAnggota.get(item.uid)?.remove();
        berkasAnggota.set(item.uid, pilihan);
    }

    editIndex === null ? anggota.push(item) : (anggota[editIndex] = item);
    tutupEditor();
    perubahanAnggota();
}

function hapusAnggota(i) {
    if (!confirm(`Hapus anggota "${anggota[i].nama_lengkap}"?`)) return;
    anggota.splice(i, 1);
    tutupEditor();
    perubahanAnggota();
}

function lihatAnggota(i) {
    const a   = anggota[i];
    const url = urlSurat(a);

    $id('inisialDetail').textContent = (a.nama_lengkap || '?').trim().charAt(0).toUpperCase();
    $id('namaDetail').textContent = a.nama_lengkap;

    $id('isiDetailAnggota').innerHTML = [['nim_nisn', 1], ['no_hp', 1], ['instansi', 2], ['jurusan', 2], ['nomor_surat_pengantar', 2]]
        .map(([k, lebar]) => `
            <div class="${lebar === 2 ? 'col-span-2' : ''}">
                <dt class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">${LABEL_ANGGOTA[k]}</dt>
                <dd class="mt-0.5 font-semibold break-words">${esc(a[k]) || '-'}</dd>
            </div>`).join('');

    const tautan = $id('lihatSuratDetail');
    tautan.classList.toggle('hidden', !url);
    if (url) tautan.href = url;
    $id('tanpaSuratDetail').classList.toggle('hidden', Boolean(url));

    $id('dialogAnggota').showModal();
}

function perubahanAnggota() {
    simpanBackup();
    renderAnggota();
    if (AUTOSAVE_AKTIF) scheduleAutoSave();   // hidden input tidak memicu event, jadi dipanggil manual
}

function initAnggota() {
    ed('no_hp').addEventListener('input', e => { e.target.value = e.target.value.replace(/\D/g, ''); });
    document.querySelectorAll('input[name="tipe"]').forEach(r => r.addEventListener('change', terapkanTipe));

    const aksi = { edit: bukaEditor, lihat: lihatAnggota, hapus: hapusAnggota };
    $id('daftarAnggota').addEventListener('click', e => {
        const tombol = e.target.closest('[data-aksi]');
        if (!tombol) return;
        tombol.closest('details').removeAttribute('open');
        aksi[tombol.dataset.aksi](Number(tombol.dataset.i));
    });

    // Menu titik tiga menutup saat klik di luar.
    document.addEventListener('click', e => {
        document.querySelectorAll('#daftarAnggota details[open]').forEach(d => {
            if (!d.contains(e.target)) d.removeAttribute('open');
        });
    });

    const editor = $id('editorAnggota');

    // Enter di editor = Simpan Anggota (bukan submit form).
    editor.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); simpanAnggota(); }
    });

    // PDF langsung dicek formatnya dan ukurannya saat dipilih.
    editor.addEventListener('change', e => {
        if (e.target.name !== 'ang_surat_pengantar' || !e.target.files[0]) return;

        const pesan = validateFile(e.target, false);
        if (pesan) { tampilkanErrorEditor(pesan); e.target.value = ''; }
    });

    terapkanTipe();
}
document.addEventListener('DOMContentLoaded', () => {
    const pulih = pulihkanBackup();   // harus sebelum bidang dimuat
    window.sinkronKombo?.();

    const bidangSiap = document.getElementById('dinasTujuan').value
        ? updateBidang(pulih?.fields.instansi_bidang_id || @json(old('instansi_bidang_id', $pendaftaran?->instansi_bidang_id)))
        : Promise.resolve();

    initAnggota();
    initBackup();
    initValidasiRealtime();
    if (AUTOSAVE_AKTIF) initAutoSave();

    // Susulkan isian yang belum sempat tersimpan ke server (setelah bidang selesai dimuat).
    if (pulih && AUTOSAVE_AKTIF) bidangSiap.then(scheduleAutoSave);
});

// ==========================================
// BACKUP LOKAL (bertahan saat halaman di-refresh)
// ==========================================
function simpanBackup() {
    const form = document.getElementById('pendaftaranForm');
    const fields = {};

    for (const [nama, nilai] of new FormData(form)) {
        if (nilai instanceof File || nama === '_token' || nama === '_method' || nama.startsWith('anggota[')) continue;
        fields[nama] = nilai;
    }

    try {
        localStorage.setItem(KUNCI_BACKUP, JSON.stringify({ fields, anggota }));
    } catch (e) { /* penyimpanan penuh atau diblokir: abaikan */ }
}

function hapusBackup() {
    try { localStorage.removeItem(KUNCI_BACKUP); } catch (e) { /* abaikan */ }
}

/** Mengisi ulang form dari backup. Mengembalikan data backup, atau null bila tidak ada. */
function pulihkanBackup() {
    let data = null;
    try { data = JSON.parse(localStorage.getItem(KUNCI_BACKUP)); } catch (e) { return null; }
    if (!data || !data.fields) return null;

    const form = document.getElementById('pendaftaranForm');
    Object.entries(data.fields).forEach(([nama, nilai]) => {
        if (nama !== 'instansi_bidang_id' && form.elements[nama]) form.elements[nama].value = nilai;
    });

    if (form.elements['tanggal_mulai'].value) {
        form.elements['tanggal_selesai'].min = form.elements['tanggal_mulai'].value;
    }

    anggota = Array.isArray(data.anggota) ? data.anggota : [];
    anggota.forEach(normalisasi);
    return data;
}

function initBackup() {
    const form = document.getElementById('pendaftaranForm');
    ['input', 'change'].forEach(evt => form.addEventListener(evt, simpanBackup));
}

    // ==========================================
    // VALIDASI FORM (real-time + sebelum submit)
    // ==========================================
    const fieldLabels = {
        dinas_id: 'Instansi / Dinas Tujuan',
        instansi_bidang_id: 'Bidang Penempatan',
        kategori: 'Kategori Pendaftar',
        nama_lengkap: 'Nama Lengkap',
        nim_nisn: 'NIM / NISN',
        instansi: 'Asal Instansi / Universitas',
        jurusan: 'Jurusan / Program Studi',
        no_hp: 'No. WhatsApp / HP Aktif',
        tanggal_mulai: 'Tanggal Mulai Magang',
        tanggal_selesai: 'Tanggal Selesai Magang',
        nomor_surat_pengantar: 'Nomor Surat Pengantar',
        tanggal_surat_pengantar: 'Tanggal Surat Pengantar',
        jabatan_penerima: 'Jabatan Penerima Surat',
        kota_tujuan: 'Kota Kampus / Sekolah',
        surat_pengantar: 'Surat Pengantar Instansi',
        proposal: 'Proposal Magang',
    };

    const validators = {
        dinas_id: v => v ? null : 'Instansi tujuan wajib dipilih.',
        instansi_bidang_id: v => v ? null : 'Bidang penempatan wajib dipilih.',
        kategori: v => v ? null : 'Kategori pendaftar wajib dipilih.',
        nama_lengkap: v => {
            if (!v.trim()) return 'Nama lengkap wajib diisi.';
            if (v.length > 255) return 'Nama lengkap maksimal 255 karakter.';
            return null;
        },
        nim_nisn: v => {
            if (!v.trim()) return 'NIM / NISN wajib diisi.';
            if (v.length > 50) return 'NIM / NISN maksimal 50 karakter.';
            return null;
        },
        instansi: v => {
            if (!v.trim()) return 'Asal instansi / universitas wajib diisi.';
            if (v.length > 255) return 'Asal instansi maksimal 255 karakter.';
            return null;
        },
        jurusan: v => {
            if (!v.trim()) return 'Jurusan / program studi wajib diisi.';
            if (v.length > 255) return 'Jurusan maksimal 255 karakter.';
            return null;
        },
        no_hp: v => {
            if (!v.trim()) return 'No. WhatsApp / HP wajib diisi.';
            if (!/^[0-9]+$/.test(v)) return 'No. HP harus berupa angka.';
            if (v.length < 10 || v.length > 15) return 'No. HP harus terdiri dari 10-15 digit.';
            return null;
        },
               tanggal_mulai: v => v ? null : 'Tanggal mulai magang wajib diisi.',

        tanggal_selesai: (v, form) => {
            if (!v) return 'Tanggal selesai magang wajib diisi.';

            const mulai = form.elements['tanggal_mulai']
                ? form.elements['tanggal_mulai'].value
                : '';

            if (mulai && v < mulai) {
                return 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.';
            }

            return null;
        },

        nomor_surat_pengantar: v =>
            v.trim() ? null : 'Nomor surat pengantar wajib diisi.',

        tanggal_surat_pengantar: v =>
            v ? null : 'Tanggal surat pengantar wajib diisi.',

        jabatan_penerima: v =>
            v.trim() ? null : 'Jabatan penerima surat wajib diisi.',

        kota_tujuan: v =>
            v.trim() ? null : 'Kota kampus / sekolah wajib diisi.',
    };

    // Surat pengantar wajib selama belum pernah ada file (baru maupun draft tanpa surat).
    // Proposal SELALU opsional.
    function isSuratPengantarRequired() {
        return !hasSuratPengantar;
    }

    function validateFile(input, required) {
        const file = input.files && input.files[0];
        if (!file) return required ? 'File wajib diunggah.' : null;
        const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');
        if (!isPdf) return 'File harus berformat PDF.';
        if (file.size > 2 * 1024 * 1024) return 'Ukuran file tidak boleh lebih dari 2MB.';
        return null;
    }

    function fieldEl(name) {
        const form = document.getElementById('pendaftaranForm');
        return form ? form.elements[name] : null;
    }
// Elemen yang menampilkan status validasi (untuk kolom pencarian: kolom ketiknya).
const tampilEl = el => el.parentElement?.querySelector('[data-kombo-input]') || el;

    function showFieldError(name, message) {
        const el = fieldEl(name);
        if (!el) return;

        let err = document.querySelector('[data-client-error="' + name + '"]');
        if (!err) {
            err = document.createElement('p');
            err.dataset.clientError = name;
            err.className = 'mt-1 text-xs text-rose-600 dark:text-rose-400 font-medium';
            el.insertAdjacentElement('afterend', err);
        }
        err.textContent = message;

        tampilEl(el).classList.remove('border-emerald-400', 'ring-emerald-100');
tampilEl(el).classList.add('border-rose-400');
    }

    function showFieldSuccess(name) {
        const el = fieldEl(name);
        if (!el) return;

        const err = document.querySelector('[data-client-error="' + name + '"]');
        if (err) err.remove();

        tampilEl(el).classList.remove('border-rose-400');
tampilEl(el).classList.add('border-emerald-400');
    }

    function clearFieldState(name) {
        const el = fieldEl(name);
        const err = document.querySelector('[data-client-error="' + name + '"]');
        if (err) err.remove();
        if (el) tampilEl(el).classList.remove('border-rose-400', 'border-emerald-400');
    }

    function validateField(name) {
        const form = document.getElementById('pendaftaranForm');
        const el = form.elements[name];
        if (!el || !validators[name]) return true;

        const value = el.value || '';

        // Field belum disentuh & masih kosong -> jangan tampilkan status apa pun dulu.
        if (value.trim() === '' && !el.dataset.touched) return true;
        el.dataset.touched = '1';

        const msg = validators[name](value, form);
        if (msg) {
            showFieldError(name, msg);
            return false;
        }
        showFieldSuccess(name);
        return true;
    }

    function showErrorSummary(errors) {
        const box = document.getElementById('clientErrorBox');
        const list = document.getElementById('clientErrorList');
        list.innerHTML = '';
        errors.forEach(e => {
            const li = document.createElement('li');
            li.textContent = e.label + ': ' + e.message;
            list.appendChild(li);
        });
        box.classList.remove('hidden');
        box.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function hideErrorSummary() {
        document.getElementById('clientErrorBox').classList.add('hidden');
    }

    // Cek satu input file; mengembalikan pesan error atau null.
    function cekBerkas(form, name, required, errors) {
        const input = form.elements[name];
        if (!input) return;

        const msg = validateFile(input, required);
        if (msg) {
            showFieldError(name, msg);
            errors.push({ field: name, label: fieldLabels[name], message: msg });
        } else if (input.files.length > 0) {
            showFieldSuccess(name);
        } else {
            clearFieldState(name);
        }
    }

    // strict = true  -> sebelum "Kirim" (semua wajib diisi)
    // strict = false -> sebelum "Simpan Draft" (kosong dilewati, yang terisi tetap dicek formatnya)
    function validateForm(strict) {
        const form = document.getElementById('pendaftaranForm');
        const errors = [];

        Object.keys(validators).forEach(name => {
            const el = form.elements[name];
            if (!el) return;

            const value = el.value || '';

            if (!strict && value.trim() === '') {
                clearFieldState(name);
                return;
            }

            el.dataset.touched = '1';
            const msg = validators[name](value, form);
            if (msg) {
                showFieldError(name, msg);
                errors.push({ field: name, label: fieldLabels[name] || name, message: msg });
            } else {
                showFieldSuccess(name);
            }
        });

        cekBerkas(form, 'surat_pengantar', strict && isSuratPengantarRequired(), errors);
        cekBerkas(form, 'proposal', false, errors);
        if (tipeDipilih() === 'kelompok') {
    const editorTerbuka = !document.getElementById('editorAnggota').classList.contains('hidden');
    const tanpaSurat = anggota.filter(a => !suratAda(a)).map(a => a.nama_lengkap);

    const msg = editorTerbuka
        ? 'Simpan atau batalkan form anggota yang sedang dibuka.'
        : strict && !anggota.length
            ? 'Tambahkan minimal satu anggota kelompok.'
            : strict && tanpaSurat.length
                ? 'Surat pengantar belum diunggah untuk: ' + tanpaSurat.join(', ') + '.'
                : null;

    if (msg) errors.push({ field: 'anggota', label: 'Anggota Kelompok', message: msg });
}
}
        if (errors.length) {
            showErrorSummary(errors);
        } else {
            hideErrorSummary();
        }

        return { valid: errors.length === 0, errors };
    }

    // Validasi saat mengetik/memilih (input/change) DAN saat pindah field (blur).
    function initValidasiRealtime() {
        const form = document.getElementById('pendaftaranForm');
        if (!form) return;

        Object.keys(validators).forEach(name => {
            const el = form.elements[name];
            if (!el) return;
            const evt = (el.tagName === 'SELECT' || el.type === 'date') ? 'change' : 'input';
            el.addEventListener(evt, () => validateField(name));
            el.addEventListener('blur', () => validateField(name));
        });

        ['surat_pengantar', 'proposal'].forEach(name => {
            const el = form.elements[name];
            if (!el) return;
            el.addEventListener('change', () => {
                const required = name === 'surat_pengantar' && isSuratPengantarRequired();
                const msg = validateFile(el, required);
                if (msg) {
                    showFieldError(name, msg);
                } else if (el.files.length > 0) {
                    showFieldSuccess(name);
                } else {
                    clearFieldState(name);
                }
            });
        });
    }

    // ==========================================
    // SIMPAN DRAFT (auto-save & manual lewat satu fungsi)
    // ==========================================
    const berkasBerubah = new Set();   // file hanya dikirim jika baru dipilih
    let adaPerubahan = false;
    let autoSaveTimer = null;
    let autoSaveInFlight = false;
    let autoSaveQueued = false;
    let autoSaveDisabled = false;
    let autoSavePromise = null;

    function kirimDraft() {
    const form = document.getElementById('pendaftaranForm');
    const formData = new FormData(form);
    formData.delete('_method');

    const dikirim = [...berkasBerubah];
    ['surat_pengantar', 'proposal'].forEach(n => {
        if (!berkasBerubah.has(n)) formData.delete(n);
    });

    // Berkas anggota yang ikut terkirim pada permintaan ini (dibersihkan setelah tersimpan).
    const terkirim = new Map(berkasAnggota);

    return fetch(URL_DRAFT, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': form.elements['_token'].value,
            'Accept': 'application/json',
        },
    }).then(async res => {
        const data = await res.json().catch(() => ({}));
        if (!res.ok || !data.success) {
            const pesanValidasi = data.errors ? Object.values(data.errors)[0][0] : null;
            throw new Error(pesanValidasi || data.message || 'Draft gagal disimpan.');
        }

        dikirim.forEach(n => berkasBerubah.delete(n));
        hasSuratPengantar = hasSuratPengantar || data.has_surat;

        if (data.surat_url) {
            const tautan = $id('lihatSuratKetua');
            tautan.href = data.surat_url;
            tautan.classList.remove('hidden');
        }

        // Anggota: simpan id dan URL surat dari server; buang kolom berkas yang sudah tersimpan.
        Object.entries(data.anggota ?? {}).forEach(([uid, info]) => {
            const a = anggota.find(x => x.uid === uid);
            if (!a) return;

            a.id = info.id;
            a.surat = info.surat;

            const el = terkirim.get(uid);
            if (el && berkasAnggota.get(uid) === el) {   // belum diganti pilihan yang lebih baru
                el.remove();
                berkasAnggota.delete(uid);
            }
        });

        renderAnggota();
        simpanBackup();

        return data;
    });
}
    function scheduleAutoSave() {
        adaPerubahan = true;
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(autoSaveDraft, 1200);
    }

    function autoSaveDraft() {
        if (autoSaveDisabled) return;
        if (!adaPerubahan && berkasBerubah.size === 0) return;   // tidak ada yang perlu disimpan
        if (autoSaveInFlight) { autoSaveQueued = true; return; }

        const statusEl = document.getElementById('autosaveStatus');
        autoSaveInFlight = true;
        adaPerubahan = false;
        if (statusEl) statusEl.textContent = 'Menyimpan...';

        autoSavePromise = kirimDraft()
            .then(() => {
    if (!adaPerubahan && berkasBerubah.size === 0) hapusBackup();   // server sudah sinkron
    if (statusEl) {
        const jam = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        statusEl.textContent = `Tersimpan otomatis pukul ${jam}`;
    }
})
.catch(error => {
    console.error('AUTO-SAVE ERROR:', error);
    adaPerubahan = true;
    if (statusEl) statusEl.textContent = 'Belum tersimpan: ' + error.message;
})
            .finally(() => {
                autoSaveInFlight = false;
                if (autoSaveQueued) {
                    autoSaveQueued = false;
                    autoSaveDraft();
                }
            });
    }

    function initAutoSave() {
        const form = document.getElementById('pendaftaranForm');
        if (!form) return;

     form.querySelectorAll('input:not([type="file"]):not([type="hidden"]):not([form]), select, textarea').forEach(el => {
            const evt = (el.tagName === 'SELECT' || el.type === 'date') ? 'change' : 'input';
            el.addEventListener(evt, scheduleAutoSave);

            // Pindah field -> simpan segera (hanya bila ada perubahan).
            el.addEventListener('blur', () => {
                clearTimeout(autoSaveTimer);
                autoSaveDraft();
            });
        });

        ['surat_pengantar', 'proposal'].forEach(name => {
            const el = form.elements[name];
            if (!el) return;
            el.addEventListener('change', () => {
                if (el.files.length) berkasBerubah.add(name);
                clearTimeout(autoSaveTimer);
                autoSaveDraft();
            });
        });
    }

    // ==========================================
    // AKSI TOMBOL
    // ==========================================
    async function simpanDraft() {
        if (!validateForm(false).valid) {
            alert('Ada isian yang formatnya belum sesuai. Silakan periksa keterangan berwarna merah pada formulir sebelum menyimpan draft.');
            return;
        }

        clearTimeout(autoSaveTimer);

        // file yang dipilih tapi belum terkirim ikut disimpan
        const form = document.getElementById('pendaftaranForm');
        ['surat_pengantar', 'proposal'].forEach(n => {
            if (form.elements[n] && form.elements[n].files.length) berkasBerubah.add(n);
        });

        const button = document.getElementById('btnSimpanDraft');
        const text = document.getElementById('textSimpanDraft');
        button.disabled = true;
        text.textContent = 'Menyimpan...';

        try {
            await autoSavePromise;                 // jangan bentrok dengan auto-save yang sedang jalan
            const data = await kirimDraft();
            hapusBackup();
            alert(data.message || 'Draft berhasil disimpan.');
            window.location.href = URL_DASHBOARD;
        } catch (error) {
            console.error('ERROR SIMPAN DRAFT:', error);
            alert(error.message || 'Draft gagal disimpan. Silakan coba lagi.');
            button.disabled = false;
            text.textContent = 'Simpan Draft';
        }
    }

    async function konfirmasiKirim() {
        if (!validateForm(true).valid) {
            alert('Masih ada data yang belum sesuai ketentuan. Silakan periksa keterangan berwarna merah pada formulir.');
            return;
        }

        if (!confirm('Yakin data yang kamu isi sudah benar? Setelah dikirim, data tidak bisa diedit lagi selama proses verifikasi.')) {
            return;
        }

        // Hentikan auto-save & tunggu yang sedang berjalan, supaya tidak muncul draft ganda.
        autoSaveDisabled = true;
        clearTimeout(autoSaveTimer);
        await autoSavePromise;
        hapusBackup();
        ['surat_pengantar', 'proposal'].forEach(n => {
    const el = fieldEl(n);
    if (el && !berkasBerubah.has(n)) el.disabled = true;   // sudah ada di server
});
        document.getElementById('pendaftaranForm').submit();
    }

    async function hapusDraft() {
        if (!confirm('Yakin ingin menghapus draft ini? Data dan berkas yang sudah diunggah akan ikut terhapus dan tidak dapat dikembalikan.')) {
            return;
        }

        // Cegah auto-save "menghidupkan" draft lagi setelah dihapus.
        autoSaveDisabled = true;
        clearTimeout(autoSaveTimer);
        await autoSavePromise;
        hapusBackup();
        document.getElementById('hapusDraftForm').submit();
    }
</script>
@endpush