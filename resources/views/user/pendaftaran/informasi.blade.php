@extends('user.layout.app')

@section('title', 'Informasi & Panduan')

@section('content')

    <section class="relative min-h-screen pt-20 pb-16 px-4 sm:px-6 overflow-hidden bg-slate-50">

        {{-- Background --}}
        <div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2
                    w-[600px] h-[600px]
                    bg-gradient-to-tr from-blue-400/20 via-cyan-400/20 to-indigo-300/20
                    rounded-full blur-[120px] pointer-events-none">
        </div>

        <div class="absolute inset-0
                    bg-[linear-gradient(to_right,#e2e8f0_1px,transparent_1px),
                    linear-gradient(to_bottom,#e2e8f0_1px,transparent_1px)]
                    bg-[size:3.5rem_3.5rem]
                    pointer-events-none opacity-50">
        </div>

        <div class="max-w-5xl mx-auto relative z-10">

            {{-- BACK --}}
            <div class="mb-4">
                @auth
                    <a href="{{ route('user.dashboard') }}"
                       class="inline-flex items-center gap-2
                              text-xs font-bold text-slate-500
                              hover:text-blue-600 transition group">

                        <span class="group-hover:-translate-x-1 transition-transform">
                            ←
                        </span>

                        Kembali ke Dashboard
                    </a>
                @else
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-2
                              text-xs font-bold text-slate-500
                              hover:text-blue-600 transition group">

                        <span class="group-hover:-translate-x-1 transition-transform">
                            ←
                        </span>

                        Kembali ke Beranda
                    </a>
                @endauth
            </div>

            {{-- CARD UTAMA --}}
            <div class="bg-white/90 backdrop-blur-xl
                        rounded-3xl
                        shadow-xl
                        border border-slate-200/80
                        overflow-hidden">


                {{-- HEADER --}}
                <div class="bg-gradient-to-b from-blue-50/70 to-white
                            px-6 py-8 sm:px-10 sm:py-10
                            text-center
                            border-b border-slate-100">

                    <span class="inline-flex items-center gap-2
                                 px-3.5 py-1.5
                                 rounded-full
                                 bg-blue-100
                                 border border-blue-200
                                 text-blue-700
                                 text-[11px] sm:text-xs
                                 font-extrabold
                                 tracking-wider uppercase">

                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>

                        Panduan & Ketentuan
                    </span>


                    <h1 class="text-2xl sm:text-3xl lg:text-4xl
                               font-extrabold
                               text-slate-900
                               mt-4
                               tracking-tight">

                        Informasi Pendaftaran
                        <span class="block text-blue-600">
                            Magang & PKL
                        </span>

                    </h1>


                    <p class="text-sm text-slate-500
                              mt-3
                              max-w-2xl
                              mx-auto
                              leading-relaxed">

                        Pelajari alur pendaftaran, persyaratan dokumen,
                        serta ketentuan sebelum mengajukan magang melalui
                        sistem MagangHub.
                    </p>
                </div>
                {{-- CONTENT --}}
                <div class="p-6 sm:p-10">

                    {{-- ================================================== --}}
                    {{-- 1. ALUR PENDAFTARAN --}}
                    {{-- ================================================== --}}

                    <div class="mb-10">
                        <x-step-heading number="1" variant="blue">
                            Alur Pendaftaran
                        </x-step-heading>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                            <x-langkah-card
                                nomor="1"
                                judul="Login Akun">
                                Masuk ke sistem menggunakan
                                akun Google yang aktif.
                            </x-langkah-card>

                            <x-langkah-card
                                nomor="2"
                                judul="Pilih Instansi">
                                Pilih kategori peserta dan
                                instansi tujuan magang yang tersedia.
                            </x-langkah-card>

                            <x-langkah-card
                                nomor="3"
                                judul="Isi Data & Berkas">
                                Lengkapi biodata dan unggah
                                dokumen persyaratan dalam format PDF.
                            </x-langkah-card>

                            <x-langkah-card
                                nomor="4"
                                judul="Pantau Status">
                                Pantau proses verifikasi admin
                                hingga hasil pengajuan diterbitkan.
                            </x-langkah-card>
                        </div>
                    </div>

                    {{-- ================================================== --}}
                    {{-- 2. PERSYARATAN --}}
                    {{-- ================================================== --}}

                    <div class="mb-10">
                        <x-step-heading number="2" variant="blue">
                            Persyaratan Dokumen
                        </x-step-heading>

                        <div class="space-y-3">

                            {{-- Surat Pengantar --}}
                            <div class="group
                                        p-4 sm:p-5
                                        bg-blue-50/60
                                        border border-blue-100
                                        rounded-2xl
                                        flex items-start gap-4
                                        hover:border-blue-200
                                        hover:shadow-sm
                                        transition">

                                <div class="w-10 h-10 shrink-0
                                            bg-white
                                            rounded-xl
                                            border border-blue-100
                                            flex items-center justify-center
                                            text-lg
                                            shadow-sm">
                                    📄
                                </div>

                                <div>
                                    <h3 class="font-bold text-sm text-slate-900">
                                        Surat Pengantar Resmi
                                        <span class="text-rose-500">
                                            *
                                        </span>
                                    </h3>

                                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                        Surat pengantar resmi dari Kampus
                                        atau Sekolah/SMK yang ditujukan
                                        kepada instansi tujuan.
                                    </p>

                                </div>

                            </div>



                            {{-- Proposal --}}
                            <div class="group
                                        p-4 sm:p-5
                                        bg-slate-50
                                        border border-slate-200
                                        rounded-2xl
                                        flex items-start gap-4
                                        hover:border-slate-300
                                        hover:shadow-sm
                                        transition">

                                <div class="w-10 h-10 shrink-0
                                            bg-white
                                            rounded-xl
                                            border border-slate-200
                                            flex items-center justify-center
                                            text-lg
                                            shadow-sm">

                                    📁

                                </div>


                                <div>

                                    <h3 class="font-bold text-sm text-slate-900">
                                        Proposal & CV
                                    </h3>

                                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                        Dokumen tambahan dapat dilampirkan
                                        apabila dipersyaratkan oleh
                                        instansi tujuan.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- ================================================== --}}
                    {{-- 3. CATATAN --}}
                    {{-- ================================================== --}}

                    <div class="mb-10">

                        <x-step-heading number="3" variant="blue">
                            Hal yang Perlu Diperhatikan
                        </x-step-heading>


                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">


                            <div class="p-5
                                        bg-amber-50
                                        border border-amber-100
                                        rounded-2xl">

                                <div class="flex items-center gap-3">

                                    <span class="w-9 h-9
                                                 rounded-xl
                                                 bg-white
                                                 border border-amber-100
                                                 flex items-center justify-center">
                                        ⚠️
                                    </span>

                                    <h3 class="font-bold text-sm text-slate-800">
                                        Periksa Data
                                    </h3>

                                </div>

                                <p class="text-xs text-slate-600 mt-3 leading-relaxed">
                                    Pastikan seluruh data dan dokumen
                                    yang diunggah sudah benar sebelum
                                    mengirimkan pengajuan.
                                </p>

                            </div>



                            <div class="p-5
                                        bg-emerald-50
                                        border border-emerald-100
                                        rounded-2xl">

                                <div class="flex items-center gap-3">

                                    <span class="w-9 h-9
                                                 rounded-xl
                                                 bg-white
                                                 border border-emerald-100
                                                 flex items-center justify-center">
                                        ✓
                                    </span>

                                    <h3 class="font-bold text-sm text-slate-800">
                                        Pantau Pengajuan
                                    </h3>

                                </div>

                                <p class="text-xs text-slate-600 mt-3 leading-relaxed">
                                    Setelah pengajuan dikirim, pantau
                                    status secara berkala melalui
                                    dashboard pengguna.
                                </p>

                            </div>

                        </div>

                    </div>



                    {{-- ================================================== --}}
                    {{-- 4. BANTUAN --}}
                    {{-- ================================================== --}}

                    <div class="relative
                                p-6 sm:p-7
                                bg-slate-900
                                text-white
                                rounded-2xl
                                flex flex-col sm:flex-row
                                items-center
                                justify-between
                                gap-5
                                overflow-hidden
                                shadow-xl">

                        <div class="absolute -right-10 -bottom-10
                                    w-32 h-32
                                    bg-blue-600/30
                                    rounded-full
                                    blur-2xl">
                        </div>


                        <div class="relative z-10
                                    text-center sm:text-left">

                            <h3 class="font-bold text-sm sm:text-base">
                                Butuh Bantuan?
                            </h3>

                            <p class="text-xs text-slate-400 mt-1">
                                Hubungi pengelola magang apabila mengalami
                                kendala saat menggunakan sistem.
                            </p>

                        </div>


                        {{-- Ganti nomor WhatsApp nanti --}}
                        <a href="https://wa.me/"
                           target="_blank"
                           class="relative z-10
                                  inline-flex
                                  items-center
                                  justify-center
                                  gap-2
                                  px-5 py-2.5
                                  bg-blue-600
                                  hover:bg-blue-500
                                  text-white
                                  text-xs
                                  font-bold
                                  rounded-xl
                                  transition
                                  shadow-lg
                                  shadow-blue-600/30
                                  whitespace-nowrap">

                            WhatsApp Admin

                            <span>
                                →
                            </span>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

@endsection