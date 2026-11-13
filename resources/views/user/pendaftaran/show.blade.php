@extends('user.layout.app')

@section('title', 'Detail Pendaftaran')

@section('content')

<x-page>

    <x-page-header
        eyebrow="Pendaftaran"
        title="Detail Pendaftaran"
    >
        Berikut adalah informasi formulir pendaftaran magang/PKL yang telah kamu kirim.
    </x-page-header>

    <div class="space-y-5">

        {{-- KEMBALI KE RIWAYAT --}}
        <a
            href="{{ route('user.pendaftaran.riwayat') }}"
            class="inline-block text-xs font-bold text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-cyan-400 transition"
        >
            ← Kembali ke Riwayat Pendaftaran
        </a>

        {{-- DETAIL DATA FORMULIR --}}
        <x-pendaftaran.detail-data :pendaftaran="$pendaftaran" />

    </div>

</x-page>

@endsection