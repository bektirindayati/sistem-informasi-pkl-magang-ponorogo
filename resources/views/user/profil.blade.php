
@extends('user.layout.app')

@section('title', 'Profil Saya')

@section('content')
<x-page>

    {{-- HEADER PROFIL --}}
    <div class="mb-6">
        <a
            href="{{ route('user.dashboard') }}"
            class="inline-flex items-center gap-2
                   text-sm font-semibold
                   text-slate-600 hover:text-blue-700
                   dark:text-slate-300 dark:hover:text-cyan-300
                   transition-colors duration-200"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Dashboard
        </a>

        <p class="mt-5 text-xs font-bold uppercase tracking-wider
                  text-blue-700 dark:text-cyan-300">
            Akun
        </p>

        <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold
                   text-slate-900 dark:text-white">
            Profil Saya
        </h1>

        <p class="mt-2 text-sm sm:text-base leading-relaxed
                  text-slate-600 dark:text-slate-300">
            Data ini otomatis terisi di formulir pendaftaran magang berikutnya.
        </p>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    @if(session('error'))
        <x-alert type="error">⚠️ {{ session('error') }}</x-alert>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- IDENTITAS AKUN --}}
        <x-card class="lg:col-span-1 !bg-white dark:!bg-slate-900
                       !border-blue-100 dark:!border-slate-700">

            <div class="flex items-center gap-4">

                @if($user->avatar)
                    <img
                        src="{{ asset('storage/' . $user->avatar) }}"
                        alt="Avatar"
                        class="w-14 h-14 rounded-full object-cover
                               border border-blue-100 dark:border-slate-700
                               shrink-0"
                    >
                @else
                    <div class="w-14 h-14 rounded-full
                                bg-blue-600 dark:bg-blue-500
                                text-white font-bold
                                flex items-center justify-center
                                text-lg shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div class="min-w-0">
                    <h2 class="text-base font-semibold text-slate-900 dark:text-white truncate">
                        {{ $user->name }}
                    </h2>

                    <p class="text-sm text-slate-600 dark:text-slate-400 truncate">
                        {{ $user->email }}
                    </p>
                </div>

            </div>

            <div class="mt-4 pt-4 border-t border-blue-100 dark:border-slate-700">
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                    Nama akun dan email mengikuti akun Google yang kamu gunakan untuk login.
                </p>
            </div>

        </x-card>

        {{-- DATA PROFIL UNTUK PENDAFTARAN --}}
        <x-card class="lg:col-span-2 !bg-white dark:!bg-slate-900
                       !border-blue-100 dark:!border-slate-700">

            <div>
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">
                    Data Profil Pendaftaran
                </h2>

                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Lengkapi data berikut agar otomatis terisi saat melakukan
                    pendaftaran magang.
                </p>
            </div>

            <form action="{{ route('user.profil.update') }}" method="POST" class="mt-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- KATEGORI --}}
                    <div class="md:col-span-2">
                        <x-form.select
                            name="kategori"
                            label="Kategori Pendaftar"
                            hint="Mahasiswa atau siswa SMK/sederajat. Dipakai otomatis di formulir pendaftaran."
                        >
                            <option value="">-- Pilih Kategori --</option>

                            @foreach (\App\Models\PendaftaranMagang::KATEGORI as $nilai => $label)
                                <option
                                    value="{{ $nilai }}"
                                    @selected(old('kategori', $user->kategori) === $nilai)
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </x-form.select>
                    </div>

                    {{-- NAMA LENGKAP --}}
                    <x-form.input
                        name="nama_lengkap"
                        label="Nama Lengkap"
                        placeholder="Nama lengkap sesuai dokumen resmi"
                        :value="old('nama_lengkap', $user->nama_lengkap)"
                    />

                    {{-- NIM / NISN --}}
                    <x-form.input
                        name="nim_nisn"
                        label="NIM / NISN"
                        placeholder="Contoh: 22050974001"
                        :value="old('nim_nisn', $user->nim_nisn)"
                    />

                    {{-- ASAL INSTANSI --}}
                    <div class="md:col-span-2">
                        <x-form.input
                            name="instansi"
                            label="Asal Instansi / Universitas"
                            placeholder="Contoh: Universitas Negeri Surabaya"
                            :value="old('instansi', $user->instansi)"
                        />
                    </div>

                    {{-- JURUSAN --}}
                    <x-form.input
                        name="jurusan"
                        label="Jurusan / Program Studi"
                        placeholder="Contoh: S1 Teknologi Pendidikan"
                        :value="old('jurusan', $user->jurusan)"
                    />

                    {{-- NOMOR HP --}}
                    <x-form.input
                        name="no_hp"
                        label="No. WhatsApp / HP"
                        placeholder="Contoh: 081234567890"
                        inputmode="numeric"
                        :value="old('no_hp', $user->no_hp)"
                        oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                    />

                    {{-- ALAMAT --}}
                    <div class="md:col-span-2">
                        <x-form.input
                            name="alamat"
                            label="Alamat"
                            placeholder="Alamat lengkap"
                            :value="old('alamat', $user->alamat)"
                        />
                    </div>

                    {{-- PROVINSI DAN KABUPATEN/KOTA --}}
                    <x-form.wilayah
                        :provinsi="old('provinsi', $user->provinsi)"
                        :kabupaten="old('kabupaten', $user->kabupaten)"
                    />

                </div>

                {{-- TOMBOL SIMPAN --}}
                <div class="mt-6 pt-5 border-t border-blue-100 dark:border-slate-700">
                    <x-button type="submit">
                        Simpan Perubahan
                    </x-button>
                </div>

            </form>

        </x-card>

    </div>

</x-page>
@endsection
