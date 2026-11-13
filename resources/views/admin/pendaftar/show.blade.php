@extends('admin.layout.app')

@section('page-title', 'Detail Pendaftar')

@section('content')

    @php $p = $pendaftaran; @endphp

    @foreach (['success' => 'emerald', 'error' => 'rose'] as $kunci => $warna)
        @if (session($kunci))
            <div class="mb-4 rounded-xl border border-{{ $warna }}-200 dark:border-{{ $warna }}-500/25
                        bg-{{ $warna }}-50 dark:bg-{{ $warna }}-500/10 px-4 py-3 text-base font-medium
                        text-{{ $warna }}-700 dark:text-{{ $warna }}-300">
                {{ session($kunci) }}
            </div>
        @endif
    @endforeach

    <a href="{{ route('admin.pendaftar.index') }}"
       class="inline-block text-sm font-semibold text-slate-500 hover:text-cyan-700 dark:text-slate-400 dark:hover:text-cyan-400 transition">
        ← Kembali ke Data Pendaftar
    </a>

    <div class="mt-3 space-y-5">

        {{-- CARD 1: DATA PENGAJUAN LENGKAP --}}
        <x-admin.ringkasan-pendaftar :pendaftaran="$p" />

        {{-- CARD 2: VERIFIKASI & DOKUMEN --}}
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900
            shadow-sm dark:shadow-none p-5 md:p-6">
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Verifikasi &amp; Dokumen</h2>

        <div class="mt-3 divide-y divide-slate-200 dark:divide-slate-700
                    border-y border-slate-200 dark:border-slate-700">
            <x-admin.status-verifikasi :pendaftaran="$p" />
            <x-admin.dokumen jenis="balasan" :pendaftaran="$p" />
            <x-admin.dokumen jenis="selesai" :pendaftaran="$p" />
            <x-admin.dokumen jenis="sertifikat" :pendaftaran="$p" />
        </div>
    </div>

    </div>

@endsection