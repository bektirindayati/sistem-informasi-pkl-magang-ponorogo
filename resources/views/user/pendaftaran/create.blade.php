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
    .form-section {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #ffffff;
        padding: 20px;
    }

    .form-section + .form-section {
        margin-top: 18px;
    }
</style>
@endpush

@section('content')
@php
    $isEdit = isset($pendaftaran) && $pendaftaran;

    $tglMulaiVal = $isEdit && $pendaftaran->tanggal_mulai
        ? \Carbon\Carbon::parse($pendaftaran->tanggal_mulai)->format('Y-m-d')
        : null;

    $tglSelesaiVal = $isEdit && $pendaftaran->tanggal_selesai
        ? \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->format('Y-m-d')
        : null;

    // AUTO-FILL: untuk pendaftaran BARU (belum ada $pendaftaran sama
    // sekali), pakai data dari Profil user kalau sudah pernah diisi.
    // Untuk draft/edit yang sudah punya datanya sendiri, data
    // $pendaftaran tetap diutamakan (tidak ditimpa data profil).
    $defaultNimNisn = $pendaftaran->nim_nisn ?? (Auth::user()->nim_nisn ?? null);
    $defaultInstansi = $pendaftaran->instansi ?? (Auth::user()->instansi ?? null);
    $defaultJurusan = $pendaftaran->jurusan ?? (Auth::user()->jurusan ?? null);

    // Dipakai oleh JS validasi: apakah file surat pengantar wajib diisi
    // ulang (hanya wajib untuk pendaftaran baru / belum pernah ada file).
    $hasSuratPengantar = $isEdit && $pendaftaran->surat_pengantar;

    // Tombol "Hapus Draft" hanya relevan saat mengedit draft milik sendiri.
    $bisaHapusDraft = $isEdit && $pendaftaran->status === 'draft';
@endphp
<div class="min-h-screen bg-slate-50 py-8 sm:py-10 px-4 sm:px-6">
    <div class="max-w-4xl mx-auto">
        {{-- MAIN CARD --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            {{-- HEADER --}}
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 sm:px-8 py-7">
                <div class="flex items-start gap-4">
                    <div class="hidden sm:flex w-12 h-12 rounded-xl bg-white/15 items-center justify-center shrink-0">
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"
                            />
                        </svg>
                    </div>

                    <div>
                        <div class="inline-flex items-center px-2.5 py-1 rounded-full bg-white/15 text-white text-[11px] font-semibold uppercase tracking-wide mb-2">
                            Portal Magang Resmi
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                            Formulir Pendaftaran
                        </h1>

                        <p class="mt-1.5 text-sm text-indigo-100 max-w-2xl leading-relaxed">
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
                    <div class="mb-5 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-sm">

                        <div class="flex items-start gap-3">

                            <svg
                                class="w-5 h-5 mt-0.5 shrink-0"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                />
                            </svg>

                            <div>
                                <p class="font-semibold">
                                    Mohon periksa kembali formulir Anda.
                                </p>

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
                <div id="clientErrorBox" class="hidden mb-5 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-sm">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="font-semibold">
                                Mohon periksa kembali formulir Anda.
                            </p>
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
                            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 4h1m4 0h1"
                                    />
                                </svg>
                            </div>

                            <div>
                                <h2 class="text-sm font-bold text-slate-800">
                                    Instansi Tujuan
                                </h2>

                                <p class="text-xs text-slate-500 mt-0.5">
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
                                onchange="updateBidang()"
                                hint="Pilih instansi tujuan magang."
                            >
                                <option
                                    value=""
                                    disabled
                                    {{ old('dinas_id', $pendaftaran->dinas_id ?? '') ? '' : 'selected' }}
                                >
                                    -- Pilih Instansi --
                                </option>
                                @foreach ($dinases as $dinas)
                                    <option
                                        value="{{ $dinas->id }}"
                                        {{ old('dinas_id', $pendaftaran->dinas_id ?? '') == $dinas->id ? 'selected' : '' }}
                                    >
                                        {{ $dinas->nama_dinas }}
                                    </option>
                                @endforeach
                            </x-form.select>

                            {{-- BIDANG --}}
                            <x-form.select
                                name="instansi_bidang_id"
                                label="Bidang Penempatan"
                                id="pilihanDivisi"
                                required
                                hint="Pilih bidang yang sesuai."
                            >
                                <option value="" disabled selected>
                                    -- Pilih instansi terlebih dahulu --
                                </option>
                            </x-form.select>
                        </div>
                    </div>

                    {{-- SECTION 1 --}}
                    <div class="form-section">
                        <x-step-heading
                            number="1"
                            variant="indigo"
                        >
                            Informasi Akademik & Data Diri
                        </x-step-heading>
                        <div class="mt-4 space-y-4">

                            {{-- KATEGORI --}}
                            <x-form.select
                                name="kategori"
                                label="Kategori Pendaftar"
                                required
                            >
                                <option value="">
                                    -- Pilih Kategori --
                                </option>
                                <option
                                    value="Mahasiswa"
                                    {{ old('kategori', $pendaftaran->kategori ?? null) == 'Mahasiswa' ? 'selected' : '' }}
                                >
                                    Mahasiswa (Magang Kuliah)
                                </option>
                                <option
                                    value="Siswa (SMK/Sederajat)"
                                    {{ old('kategori', $pendaftaran->kategori ?? null) == 'Siswa (SMK/Sederajat)' ? 'selected' : '' }}
                                >
                                    Siswa (SMK/Sederajat)
                                </option>
                            </x-form.select>

                            {{-- DATA AKADEMIK --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <x-form.input
                                    name="nama_lengkap"
                                    label="Nama Lengkap"
                                    required
                                    :value="$pendaftaran->nama_lengkap ?? (Auth::user()->name ?? '')"
                                />
                                <x-form.input
                                    name="nim_nisn"
                                    id="nim_nisn"
                                    label="NIM / NISN"
                                    required
                                    placeholder="Contoh: 22050974001"
                                    :value="$defaultNimNisn"
                                    oninput="this.classList.remove('border-rose-500','bg-rose-50/30');"
                                />
                                <x-form.input
                                    name="instansi"
                                    label="Asal Instansi / Universitas"
                                    required
                                    placeholder="Universitas Negeri Surabaya"
                                    :value="$defaultInstansi"
                                />
                                <x-form.input
                                    name="jurusan"
                                    label="Jurusan / Program Studi"
                                    required
                                    placeholder="S1 Teknologi Pendidikan"
                                    :value="$defaultJurusan"
                                />
                                <div class="md:col-span-2">
                                    <x-form.input
                                        name="no_hp"
                                        id="no_hp"
                                        label="No. WhatsApp / HP Aktif"
                                        required
                                        placeholder="Contoh: 081234567890"
                                        inputmode="numeric"
                                        :value="$pendaftaran->no_hp ?? null"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                                    />
                                </div>
                                <div class="md:col-span-2">
                                    <x-form.input
                                        name="alamat"
                                        label="Alamat Tempat Tinggal"
                                        required
                                        placeholder="Jl. Ketintang No. 156, RT/RW, Desa/Kelurahan"
                                        :value="$pendaftaran->alamat ?? null"
                                    />
                                </div>

                                {{-- KABUPATEN --}}
                                <x-form.input
                                    name="kabupaten"
                                    id="input-kabupaten"
                                    label="Kabupaten / Kota"
                                    required
                                    list="list-kabupaten"
                                    placeholder="Contoh: Ponorogo"
                                    :value="$pendaftaran->kabupaten ?? null"
                                    oninput="autoFillProvinsi(this.value)"
                                />
                                <datalist id="list-kabupaten">
                                    @foreach ([
                                        'Surabaya',
                                        'Sidoarjo',
                                        'Gresik',
                                        'Mojokerto',
                                        'Malang',
                                        'Ponorogo',
                                        'Madiun',
                                        'Kediri',
                                        'Jember',
                                        'Banyuwangi'
                                    ] as $kab)

                                        <option value="{{ $kab }}">
                                    @endforeach
                                </datalist>

                                {{-- PROVINSI --}}
                                <x-form.input
                                    name="provinsi"
                                    id="input-provinsi"
                                    label="Provinsi"
                                    required
                                    readonly
                                    placeholder="Jawa Timur"
                                    :value="$pendaftaran->provinsi ?? null"
                                />
                            </div>
                        </div>
                    </div>


                    {{-- SECTION 2 --}}
                    <div class="form-section">
                        <x-step-heading
                            number="2"
                            variant="indigo"
                        >
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
                                onchange="
                                    document.getElementById('tanggal_selesai').min=this.value;
                                "
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
                        <x-step-heading
                            number="3"
                            variant="indigo"
                        >
                            Unggah Berkas Persyaratan
                        </x-step-heading>
                        <div class="mt-3 mb-4 ml-0 sm:ml-10">
                            <div class="flex items-start gap-2 text-xs text-slate-500">
                                <svg
                                    class="w-4 h-4 mt-0.5 text-indigo-500 shrink-0"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-8h.01M12 20a8 8 0 100-16 8 8 0 000 16z"
                                    />
                                </svg>
                                <p>
                                    Format file wajib
                                    <strong class="text-slate-700">PDF</strong>
                                    dengan ukuran maksimal
                                    <strong class="text-slate-700">2MB</strong>
                                    per file.
                                    @if($isEdit)
                                        <span class="block mt-1">
                                            Kosongkan file jika tidak ingin mengganti dokumen yang sudah ada.
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <x-form.file
                                name="surat_pengantar"
                                label="Surat Pengantar Instansi"
                                required
                                accept=".pdf"
                                :hint="$isEdit && $pendaftaran->surat_pengantar
                                    ? 'File saat ini: '.$pendaftaran->surat_pengantar
                                : null"
                                />
                            <x-form.file
                                name="proposal"
                                label="Proposal Magang"
                                accept=".pdf"
                                :hint="$isEdit && $pendaftaran->proposal
                                    ? 'File saat ini: '.$pendaftaran->proposal
                                : null"
                                />
                        </div>
                    </div>
{{-- ACTION --}}
<div class="pt-5 border-t border-slate-200">

    <div class="flex flex-col sm:flex-row items-center 
    justify-between gap-4">
        {{-- KEMBALI --}}
        <a
            href="{{ route('user.dashboard') }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition"
        >
            <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                />
            </svg>

            Kembali ke Dashboard
        </a>
                            {{-- BUTTON --}}
                            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">

                                @if($bisaHapusDraft)
                                {{-- HAPUS DRAFT --}}
                                <button
                                    type="button"
                                    onclick="hapusDraft()"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-white text-rose-600 border border-rose-200 hover:border-rose-300 hover:bg-rose-50 rounded-xl font-semibold transition text-sm"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                    Hapus Draft
                                </button>
                                @endif

                                {{-- DRAFT --}}
                               <button
    type="button"
    id="btnSimpanDraft"
    onclick="simpanDraft()"
    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-slate-700 border border-slate-300 hover:border-indigo-300 hover:bg-indigo-50 rounded-xl font-semibold transition"
>
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
        viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2">
        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
        <polyline points="17 21 17 13 7 13 7 21"/>
        <polyline points="7 3 7 8 15 8"/>
    </svg>
    <span id="textSimpanDraft">Simpan Draft</span>
</button>
                                {{-- KIRIM --}}
                                <button
                                    type="button"
                                    onclick="konfirmasiKirim()"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-sm hover:shadow-md transition"
                                >
                                    <svg
                                        class="w-4 h-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 12h14m-6-6l6 6-6 6"
                                        />
                                    </svg>Kirim Pendaftaran
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                @if($bisaHapusDraft)
                <form id="hapusDraftForm"
                      action="{{ route('user.pendaftaran.draft.destroy', $pendaftaran->id) }}"
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

            <p class="text-xs text-slate-400">
                Pastikan seluruh data dan dokumen yang Anda masukkan sudah benar sebelum dikirim.
            </p>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<script>
    // KONTEKS FORM (untuk validasi JS)//
    const isEditForm = @json($isEdit);
    const hasSuratPengantar = @json($hasSuratPengantar);

    // BIDANG INSTANSI//
    function updateBidang(selectedBidangId = null) {

        const dinasId = document.getElementById('dinasTujuan').value;
        const divisiSelect = document.getElementById('pilihanDivisi');

        divisiSelect.innerHTML = '';

        if (!dinasId) {
            divisiSelect.innerHTML =
                '<option value="" disabled selected>-- Pilih instansi terlebih dahulu --</option>';
            return;
        }

        divisiSelect.innerHTML =
            '<option value="" disabled selected>Memuat bidang...</option>';

        fetch(`/user/pendaftaran/bidang/${dinasId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal mengambil data bidang.');
                }
                return response.json();
            })
            .then(data => {

                divisiSelect.innerHTML =
                    '<option value="" disabled>-- Pilih Bidang / Divisi Tujuan --</option>';

                data.forEach(bidang => {
                    const option = document.createElement('option');
                    option.value = bidang.id;
                    option.textContent = bidang.nama_bidang;
                    if (selectedBidangId && selectedBidangId == bidang.id) {
                        option.selected = true;
                    }
                    divisiSelect.appendChild(option);
                });

                if (data.length === 0) {
                    divisiSelect.innerHTML =
                        '<option value="" disabled selected>Tidak ada bidang tersedia</option>';
                }

                // Select ini dibuat ulang tiap kali dinas berganti, jadi
                // listener auto-save & validasi lama ikut hilang.
                // Pasang ulang di sini.
                divisiSelect.addEventListener('change', () => {
                    validateField('instansi_bidang_id');
                    scheduleAutoSave();
                });

            })
            .catch(error => {
                console.error(error);
                divisiSelect.innerHTML =
                    '<option value="" disabled selected>Gagal memuat bidang</option>';
            });
    }

    // DATA WILAYAH//
    const dataWilayah = {
        "Surabaya": "Jawa Timur",
        "Sidoarjo": "Jawa Timur",
        "Gresik": "Jawa Timur",
        "Mojokerto": "Jawa Timur",
        "Malang": "Jawa Timur",
        "Ponorogo": "Jawa Timur",
        "Madiun": "Jawa Timur",
        "Kediri": "Jawa Timur",
        "Jember": "Jawa Timur",
        "Banyuwangi": "Jawa Timur"
    };

    // AUTOFILL PROVINSI//
    function autoFillProvinsi(kabupaten) {
        const inputProvinsi = document.getElementById('input-provinsi');
        if (inputProvinsi) {
            inputProvinsi.value = dataWilayah[kabupaten] || "";
        }
        validateField('provinsi');
    }

    // SAAT HALAMAN SELESAI DIMUAT//
    document.addEventListener("DOMContentLoaded", function () {
        const selectedDinas =
            "{{ old('dinas_id', $pendaftaran->dinas_id ?? '') }}";
        const selectedBidang =
            "{{ old('instansi_bidang_id', $pendaftaran->instansi_bidang_id ?? '') }}";

        if (selectedDinas) {
            const dinasElement = document.getElementById('dinasTujuan');
            if (dinasElement) {
                dinasElement.value = selectedDinas;
                updateBidang(selectedBidang);
            }
        }

        const inputKabupaten = document.getElementById('input-kabupaten');
        if (inputKabupaten && inputKabupaten.value) {
            autoFillProvinsi(inputKabupaten.value);
        }

        initValidasiRealtime();
        initAutoSave();
    });

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
        alamat: 'Alamat Tempat Tinggal',
        kabupaten: 'Kabupaten / Kota',
        provinsi: 'Provinsi',
        tanggal_mulai: 'Tanggal Mulai Magang',
        tanggal_selesai: 'Tanggal Selesai Magang',
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
        alamat: v => v.trim() ? null : 'Alamat tempat tinggal wajib diisi.',
        kabupaten: v => v.trim() ? null : 'Kabupaten / kota wajib diisi.',
        provinsi: v => v.trim() ? null : 'Provinsi wajib terisi (pilih kabupaten/kota terlebih dahulu).',
        tanggal_mulai: v => v ? null : 'Tanggal mulai magang wajib diisi.',
        tanggal_selesai: (v, form) => {
            if (!v) return 'Tanggal selesai magang wajib diisi.';
            const mulai = form.elements['tanggal_mulai'] ? form.elements['tanggal_mulai'].value : '';
            if (mulai && v < mulai) return 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.';
            return null;
        },
    };

    // surat_pengantar WAJIB untuk pendaftaran baru / belum pernah ada file.
    // Saat edit dengan file lama sudah ada, boleh dikosongkan (tidak wajib upload ulang).
    // proposal SELALU opsional, apa pun kondisinya.
    function isSuratPengantarRequired() {
        return !isEditForm && !hasSuratPengantar;
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

    function showFieldError(name, message) {
        const el = fieldEl(name);
        if (!el) return;

        let err = document.querySelector('[data-client-error="' + name + '"]');
        if (!err) {
            err = document.createElement('p');
            err.dataset.clientError = name;
            err.className = 'mt-1 text-xs text-rose-600 font-medium';
            el.insertAdjacentElement('afterend', err);
        }
        err.textContent = message;

        el.classList.remove('border-emerald-400', 'ring-emerald-100');
        el.classList.add('border-rose-400');
    }

    function showFieldSuccess(name) {
        const el = fieldEl(name);
        if (!el) return;

        const err = document.querySelector('[data-client-error="' + name + '"]');
        if (err) err.remove();

        el.classList.remove('border-rose-400');
        el.classList.add('border-emerald-400');
    }

    function clearFieldState(name) {
        const el = fieldEl(name);
        const err = document.querySelector('[data-client-error="' + name + '"]');
        if (err) err.remove();
        if (el) el.classList.remove('border-rose-400', 'border-emerald-400');
    }

    function validateField(name) {
        const form = document.getElementById('pendaftaranForm');
        const el = form.elements[name];
        if (!el || !validators[name]) return true;

        const value = el.value || '';

        // Field belum disentuh & masih kosong -> jangan tampilkan status apa pun dulu.
        if (value.trim() === '' && !el.dataset.touched) {
            return true;
        }
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

    // strict = true  -> dipakai sebelum "Kirim Pendaftaran" (semua wajib diisi)
    // strict = false -> dipakai sebelum "Simpan Draft" (field kosong dilewati,
    //                   tapi field yang SUDAH diisi tetap dicek formatnya)
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

        // SURAT PENGANTAR: wajib hanya saat kirim (strict) & memang belum ada file.
        const suratInput = form.elements['surat_pengantar'];
        if (suratInput) {
            const required = strict && isSuratPengantarRequired();
            const msg = validateFile(suratInput, required);
            if (msg) {
                showFieldError('surat_pengantar', msg);
                errors.push({ field: 'surat_pengantar', label: fieldLabels.surat_pengantar, message: msg });
            } else if (suratInput.files.length > 0) {
                showFieldSuccess('surat_pengantar');
            } else {
                clearFieldState('surat_pengantar');
            }
        }

        // PROPOSAL: selalu opsional.
        const proposalInput = form.elements['proposal'];
        if (proposalInput) {
            const msg = validateFile(proposalInput, false);
            if (msg) {
                showFieldError('proposal', msg);
                errors.push({ field: 'proposal', label: fieldLabels.proposal, message: msg });
            } else if (proposalInput.files.length > 0) {
                showFieldSuccess('proposal');
            } else {
                clearFieldState('proposal');
            }
        }

        if (errors.length) {
            showErrorSummary(errors);
        } else {
            hideErrorSummary();
        }

        return { valid: errors.length === 0, errors };
    }

    // Validasi setiap field terjadi saat: mengetik/memilih (input/change)
    // DAN saat pindah ke field lain (blur) — jadi field sebelumnya
    // langsung ketahuan benar/salah begitu user pindah pertanyaan.
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
                el.dataset.touched = '1';
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
    // AUTO-SAVE DRAFT (silent, background)
    // ==========================================
    let autoSaveTimer = null;
    let autoSaveInFlight = false;
    let autoSaveQueued = false;

    function scheduleAutoSave() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(autoSaveDraft, 1200);
    }

    function autoSaveDraft() {
        if (autoSaveInFlight) {
            autoSaveQueued = true;
            return;
        }

        const form = document.getElementById('pendaftaranForm');
        if (!form) return;

        const statusEl = document.getElementById('autosaveStatus');
        const csrfInput = form.querySelector('input[name="_token"]');
        const csrfToken = csrfInput ? csrfInput.value : '';
        if (!csrfToken) return;

        const formData = new FormData(form);
        formData.delete('_method');

        autoSaveInFlight = true;
        if (statusEl) statusEl.textContent = 'Menyimpan...';

        fetch("{{ route('user.pendaftaran.draft') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const data = await response.json().catch(() => ({}));
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Gagal menyimpan otomatis.');
            }
            return data;
        })
        .then(() => {
            if (statusEl) {
                const jam = new Date().toLocaleTimeString('id-ID', {
                    hour: '2-digit', minute: '2-digit'
                });
                statusEl.textContent = `Tersimpan otomatis pukul ${jam}`;
            }
        })
        .catch(error => {
            console.error('AUTO-SAVE ERROR:', error);
            if (statusEl) statusEl.textContent = 'Gagal menyimpan otomatis.';
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

    // ==========================================
    // AUTO-SAVE SAAT DATA BERUBAH
    // ==========================================
    const fields = form.querySelectorAll(
        'input:not([type="file"]), select, textarea'
    );

    fields.forEach(el => {
        const evt =
            (el.tagName === 'SELECT' || el.type === 'date')
                ? 'change'
                : 'input';

        el.addEventListener(evt, scheduleAutoSave);
    });

    // ==========================================
    // AUTO-SAVE SAAT PINDAH DARI FIELD
    // ==========================================
    fields.forEach(el => {
        el.addEventListener('blur', () => {
            clearTimeout(autoSaveTimer);
            autoSaveDraft();
        });
    });

    // ==========================================
    // AUTO-SAVE FILE
    // ==========================================
    ['surat_pengantar', 'proposal'].forEach(name => {
        const el = form.elements[name];

        if (el) {
            el.addEventListener('change', () => {
                clearTimeout(autoSaveTimer);
                autoSaveDraft();
            });
        }
    });
}

    // KIRIM PENDAFTARAN//
    function konfirmasiKirim() {
        const hasil = validateForm(true);

        if (!hasil.valid) {
            alert('Masih ada data yang belum sesuai ketentuan. Silakan periksa keterangan berwarna merah pada formulir.');
            return;
        }

        const yakin = confirm(
            "Yakin data yang kamu isi sudah benar? Setelah dikirim, data tidak bisa diedit lagi selama proses verifikasi."
        );
        if (!yakin) return;

        const form = document.getElementById('pendaftaranForm');
        if (!form) {
            alert('Form pendaftaran tidak ditemukan!');
            return;
        }
        form.submit();
    }

    // SIMPAN DRAFT (manual)//
    function simpanDraft() {
        const hasil = validateForm(false);

        if (!hasil.valid) {
            alert('Ada isian yang formatnya belum sesuai. Silakan periksa keterangan berwarna merah pada formulir sebelum menyimpan draft.');
            return;
        }

        clearTimeout(autoSaveTimer);

        const form = document.getElementById('pendaftaranForm');
        const button = document.getElementById('btnSimpanDraft');
        const text = document.getElementById('textSimpanDraft');

        if (!form) {
            alert('Form pendaftaran tidak ditemukan!');
            return;
        }

        const formData = new FormData(form);
        formData.delete('_method');

        const csrfInput = form.querySelector('input[name="_token"]');
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfInput
            ? csrfInput.value
            : (csrfMeta ? csrfMeta.getAttribute('content') : '');

        if (!csrfToken) {
            alert('Token keamanan (CSRF) tidak ditemukan. Silakan muat ulang halaman lalu coba lagi.');
            return;
        }

        button.disabled = true;
        text.textContent = 'Menyimpan...';

        fetch("{{ route('user.pendaftaran.draft') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Draft gagal disimpan.');
            }
            return data;
        })
        .then(data => {
            if (data.success) {
                alert(data.message || 'Draft berhasil disimpan.');
                window.location.href = "{{ route('user.dashboard') }}";
            } else {
                throw new Error(data.message || 'Draft gagal disimpan.');
            }
        })
        .catch(error => {
            console.error('ERROR SIMPAN DRAFT:', error);
            alert(error.message || 'Draft gagal disimpan. Silakan coba lagi.');
            button.disabled = false;
            text.textContent = 'Simpan Draft';
        });
    }

    // HAPUS DRAFT//
    function hapusDraft() {
        const yakin = confirm(
            'Yakin ingin menghapus draft ini? Data dan berkas yang sudah diunggah akan ikut terhapus dan tidak dapat dikembalikan.'
        );
        if (!yakin) return;

        const form = document.getElementById('hapusDraftForm');
        if (!form) {
            alert('Form hapus draft tidak ditemukan!');
            return;
        }
        form.submit();
    }
</script>
@endpush