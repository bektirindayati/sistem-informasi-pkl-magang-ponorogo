@extends('user.layout.app')

@section('title', 'Informasi & Panduan')

@section('content')

<x-page>

    <x-page-header
        eyebrow="Panduan & Ketentuan"
        title="Informasi Pendaftaran Magang & PKL"
    >
        Pelajari alur pendaftaran, persyaratan dokumen, serta ketentuan sebelum
        mengajukan magang melalui sistem SiMagang.
    </x-page-header>

    {{-- 1. ALUR PENDAFTARAN --}}
    <x-card>
        <x-step-heading number="1" variant="blue">
            Alur Pendaftaran
        </x-step-heading>

        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">

            <x-langkah-card nomor="1" judul="Login Akun">
                Masuk ke sistem menggunakan akun Google yang aktif.
            </x-langkah-card>

            <x-langkah-card nomor="2" judul="Pilih Instansi">
                Pilih instansi atau dinas tujuan serta bidang penempatan yang tersedia.
            </x-langkah-card>

            <x-langkah-card nomor="3" judul="Pilih Kategori">
                Tentukan jenis pendaftaran, yaitu Mandiri / Sendiri atau Berkelompok.
            </x-langkah-card>

            <x-langkah-card nomor="4" judul="Isi Data & Berkas">
                Lengkapi data diri, data akademik, periode magang, dan dokumen persyaratan. Jika berkelompok, lengkapi juga data diri anggota kelompok.
            </x-langkah-card>

            <x-langkah-card nomor="5" judul="Pantau Status">
                Pantau proses verifikasi admin hingga hasil pengajuan diterbitkan.
            </x-langkah-card>

        </div>
    </x-card>

    {{-- 2 & 3. PERSYARATAN + CATATAN --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

        {{-- PERSYARATAN DOKUMEN --}}
        <x-card>
            <x-step-heading number="2" variant="blue">
                Persyaratan Dokumen
            </x-step-heading>

            <div class="mt-5 space-y-3">

                {{-- SURAT PENGANTAR --}}
                <div class="p-4
                            bg-blue-50/70 dark:bg-blue-500/10
                            border border-blue-100 dark:border-blue-500/20
                            rounded-xl flex items-start gap-4">

                    <div class="w-10 h-10 shrink-0
                                bg-white dark:bg-slate-800
                                rounded-lg border border-blue-100 dark:border-blue-500/20
                                flex items-center justify-center text-lg">
                        📄
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                            Surat Pengantar Resmi <span class="text-rose-500">*</span>
                        </h3>

                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                            Surat pengantar resmi dari Kampus atau Sekolah/SMK yang ditujukan kepada instansi tujuan, diunggah dalam format PDF.
                        </p>

                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-2 leading-relaxed">
                            Untuk pendaftaran berkelompok, setiap anggota juga harus melengkapi surat pengantar masing-masing.
                        </p>
                    </div>
                </div>

                {{-- PROPOSAL DAN CV --}}
                <div class="p-4
                            bg-slate-50 dark:bg-slate-800/50
                            border border-slate-200 dark:border-slate-700
                            rounded-xl flex items-start gap-4">

                    <div class="w-10 h-10 shrink-0
                                bg-white dark:bg-slate-800
                                rounded-lg border border-slate-200 dark:border-slate-700
                                flex items-center justify-center text-lg">
                        📁
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                            Proposal & CV
                        </h3>

                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                            Dokumen tambahan dapat diunggah apabila dipersyaratkan oleh instansi tujuan.
                        </p>
                    </div>
                </div>

            </div>
        </x-card>

        {{-- HAL YANG PERLU DIPERHATIKAN --}}
        <x-card>
            <x-step-heading number="3" variant="blue">
                Hal yang Perlu Diperhatikan
            </x-step-heading>

            <div class="mt-5 space-y-3">

                {{-- PERIKSA DATA --}}
                <div class="p-4
                            bg-amber-50 dark:bg-amber-500/10
                            border border-amber-200/70 dark:border-amber-500/20
                            rounded-xl flex items-start gap-4">

                    <span class="w-10 h-10 shrink-0
                                 rounded-lg bg-white dark:bg-slate-800
                                 border border-amber-100 dark:border-amber-500/20
                                 flex items-center justify-center text-lg">
                        ⚠️
                    </span>

                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                            Periksa Data
                        </h3>

                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                            Pastikan seluruh data dan dokumen yang diunggah sudah benar sebelum mengirimkan pengajuan.
                        </p>
                    </div>
                </div>

                {{-- PANTAU PENGAJUAN --}}
                <div class="p-4
                            bg-emerald-50 dark:bg-emerald-500/10
                            border border-emerald-200/70 dark:border-emerald-500/20
                            rounded-xl flex items-start gap-4">

                    <span class="w-10 h-10 shrink-0
                                 rounded-lg bg-white dark:bg-slate-800
                                 border border-emerald-100 dark:border-emerald-500/20
                                 flex items-center justify-center text-lg">
                        ✓
                    </span>

                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">
                            Pantau Pengajuan
                        </h3>

                        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                            Setelah pengajuan dikirim, pantau status secara berkala melalui dashboard pengguna.
                        </p>
                    </div>
                </div>

            </div>
        </x-card>

    </div>

</x-page>
@endsection