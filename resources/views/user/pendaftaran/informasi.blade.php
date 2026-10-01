@extends('user.layout.app')

@section('title', 'Informasi & Panduan')

@section('content')
@php
    $kembali = auth()->check() ? route('user.dashboard') : route('home');
    $labelKembali = auth()->check() ? 'Kembali ke Dashboard' : 'Kembali ke Beranda';
@endphp

<x-page>

    <x-page-header
        eyebrow="Panduan & Ketentuan"
        title="Informasi Pendaftaran Magang & PKL"
        :back="$kembali"
        :back-label="$labelKembali"
    >
        Pelajari alur pendaftaran, persyaratan dokumen, serta ketentuan sebelum
        mengajukan magang melalui sistem SiMagang.
    </x-page-header>

    {{-- 1. ALUR PENDAFTARAN --}}
    <x-card>
        <x-step-heading number="1" variant="blue">
            Alur Pendaftaran
        </x-step-heading>

        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-langkah-card nomor="1" judul="Login Akun">
                Masuk ke sistem menggunakan akun Google yang aktif.
            </x-langkah-card>

            <x-langkah-card nomor="2" judul="Pilih Instansi">
                Pilih kategori peserta dan instansi tujuan magang yang tersedia.
            </x-langkah-card>

            <x-langkah-card nomor="3" judul="Isi Data & Berkas">
                Lengkapi biodata dan unggah dokumen persyaratan dalam format PDF.
            </x-langkah-card>

            <x-langkah-card nomor="4" judul="Pantau Status">
                Pantau proses verifikasi admin hingga hasil pengajuan diterbitkan.
            </x-langkah-card>
        </div>
    </x-card>

    {{-- 2 & 3. PERSYARATAN + CATATAN --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">

        <x-card>
            <x-step-heading number="2" variant="blue">
                Persyaratan Dokumen
            </x-step-heading>

            <div class="mt-5 space-y-3">
                <div class="p-4 bg-blue-50/60 border border-blue-100 rounded-xl flex items-start gap-4">
                    <div class="w-10 h-10 shrink-0 bg-white rounded-lg border border-blue-100 flex items-center justify-center text-lg">📄</div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">
                            Surat Pengantar Resmi <span class="text-rose-500">*</span>
                        </h3>
                        <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                            Surat pengantar resmi dari Kampus atau Sekolah/SMK yang ditujukan kepada instansi tujuan.
                        </p>
                    </div>
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-start gap-4">
                    <div class="w-10 h-10 shrink-0 bg-white rounded-lg border border-slate-200 flex items-center justify-center text-lg">📁</div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Proposal & CV</h3>
                        <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                            Dokumen tambahan dapat dilampirkan apabila dipersyaratkan oleh instansi tujuan.
                        </p>
                    </div>
                </div>
            </div>
        </x-card>

        <x-card>
            <x-step-heading number="3" variant="blue">
                Hal yang Perlu Diperhatikan
            </x-step-heading>

            <div class="mt-5 space-y-3">
                <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl flex items-start gap-4">
                    <span class="w-10 h-10 shrink-0 rounded-lg bg-white border border-amber-100 flex items-center justify-center text-lg">⚠️</span>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Periksa Data</h3>
                        <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                            Pastikan seluruh data dan dokumen yang diunggah sudah benar sebelum mengirimkan pengajuan.
                        </p>
                    </div>
                </div>

                <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-start gap-4">
                    <span class="w-10 h-10 shrink-0 rounded-lg bg-white border border-emerald-100 flex items-center justify-center text-lg">✓</span>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Pantau Pengajuan</h3>
                        <p class="text-sm text-slate-600 mt-1 leading-relaxed">
                            Setelah pengajuan dikirim, pantau status secara berkala melalui dashboard pengguna.
                        </p>
                    </div>
                </div>
            </div>
        </x-card>
    </div>

    {{-- 4. BANTUAN --}}
    <div class="rounded-2xl bg-slate-900 text-white p-6 sm:p-7
                flex flex-col sm:flex-row items-center justify-between gap-5">

        <div class="text-center sm:text-left">
            <h3 class="text-base font-semibold">Butuh Bantuan?</h3>
            <p class="text-sm text-slate-400 mt-1">
                Hubungi pengelola magang apabila mengalami kendala saat menggunakan sistem.
            </p>
        </div>

        {{-- TODO: isi nomor WhatsApp admin, mis. https://wa.me/62812xxxxxxx --}}
        <x-button href="https://wa.me/" target="_blank" rel="noopener" variant="light" class="whitespace-nowrap">
            WhatsApp Admin
        </x-button>
    </div>

</x-page>
@endsection