@extends('admin.layout.app')

@section('page-title', 'Detail Pendaftar')

@section('content')

    @php $p = $pendaftaran; @endphp

    @if (session('success'))
        <x-alert type="success"> {{ session('success') }}</x-alert>
    @endif
    @if (session('error'))
        <x-alert type="error"> {{ session('error') }}</x-alert>
    @endif

    <a href="{{ route('admin.pendaftar.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-semibold transition
              text-slate-500 hover:text-cyan-700 dark:text-slate-400 dark:hover:text-cyan-400">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="m15 19-7-7 7-7"/>
        </svg>
        Kembali ke Data Pendaftar
    </a>

    <div class="space-y-5">

        {{-- CARD 1: DATA PENGAJUAN LENGKAP --}}
        <x-admin.ringkasan-pendaftar :pendaftaran="$p" />

        {{-- CARD 2: VERIFIKASI & DOKUMEN --}}
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900
                    shadow-sm dark:shadow-none p-5 md:p-6">
            <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">Verifikasi &amp; Dokumen</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Tentukan status pendaftaran, lalu kelola surat dan sertifikat.
            </p>

            <div class="mt-4 divide-y divide-slate-200 dark:divide-slate-700
                        border-y border-slate-200 dark:border-slate-700">
                <x-admin.status-verifikasi :pendaftaran="$p" />
                <x-admin.dokumen jenis="balasan" :pendaftaran="$p" />
                <x-admin.dokumen jenis="selesai" :pendaftaran="$p" />
                <x-admin.sertifikat :pendaftaran="$p" />
            </div>
        </div>

    </div>

@endsection