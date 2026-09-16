@extends('admin.layout.app')

@section('page-title', 'Data Seluruh Pendaftar')

@section('content')

    {{-- =========================================================
        PENCARIAN
        Berlaku untuk seluruh data yang ditampilkan
        ========================================================= --}}
    <form
        method="GET"
        class="bg-white rounded-2xl border border-slate-200
               p-4 shadow-sm flex gap-3"
    >
        <input
            type="text"
            name="search"
            value="{{ $search ?? '' }}"
            placeholder="Cari nama pendaftar..."
            class="flex-1 px-4 py-2 rounded-xl
                   border border-slate-200
                   text-sm
                   focus:outline-none
                   focus:border-cyan-600"
        >

        <button
            type="submit"
            class="px-5 py-2
                   bg-slate-800
                   hover:bg-slate-900
                   text-white
                   text-sm font-bold
                   rounded-xl
                   transition"
        >
            Cari
        </button>

        @if($search ?? null)
            <a
                href="{{ route('admin.pendaftar.index') }}"
                class="px-4 py-2
                       text-slate-500
                       hover:text-slate-800
                       text-sm font-bold
                       transition"
            >
                Reset
            </a>
        @endif
    </form>


    {{-- =========================================================
        1. DAFTAR PEMAGANG AKTIF / PROSES
        ========================================================= --}}
    <x-admin.table-card
        icon="🟢"
        title="Daftar Pemagang Aktif / Proses"
        description="Peserta yang sedang menjalani magang atau dalam tahap verifikasi."
    >

        <table class="w-full text-left border-collapse min-w-[700px]">

            <thead>
                <tr
                    class="bg-slate-50
                           border-b border-slate-200
                           text-slate-600
                           font-bold text-xs
                           uppercase tracking-wider"
                >
                    <th class="p-4 px-6">Nama Pendaftar</th>
                    <th class="p-4 px-6">Asal Instansi / Sekolah</th>
                    <th class="p-4 px-6">Periode Magang</th>
                    <th class="p-4 px-6">Status</th>
                    <th class="p-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody
                class="divide-y divide-slate-100
                       text-sm font-medium
                       text-slate-700"
            >

                @forelse($pemagangAktif ?? [] as $item)

                    @php
                        $st = strtolower($item->status ?? 'pending');
                    @endphp

                    <tr class="hover:bg-slate-50/50 transition">

                        {{-- Nama --}}
                        <td class="p-4 px-6 font-bold text-slate-900">
                            {{ $item->nama_lengkap ?? '-' }}
                        </td>

                        {{-- Instansi --}}
                        <td class="p-4 px-6 text-slate-600">
                            {{ $item->instansi ?? $item->asal_sekolah ?? '-' }}
                        </td>

                        {{-- Periode --}}
                        <td class="p-4 px-6 text-xs text-slate-500">

                            @if($item->tanggal_mulai && $item->tanggal_selesai)

                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                -
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}

                            @else
                                -
                            @endif

                        </td>

                        {{-- Status --}}
                        <td class="p-4 px-6">

                            <span
                                class="px-2.5 py-1
                                       rounded-full
                                       text-xs font-bold
                                       {{ $st == 'diterima'
                                            ? 'bg-emerald-50 text-emerald-700'
                                            : ($st == 'revisi'
                                                ? 'bg-orange-50 text-orange-700'
                                                : 'bg-amber-50 text-amber-700') }}"
                            >
                                {{ ucfirst($st) }}
                            </span>

                        </td>

                        {{-- Aksi --}}
                        <td class="p-4 px-6 text-center">

                            <a
                                href="{{ route('admin.pendaftar.show', $item->id) }}"
                                class="px-3 py-1.5
                                       bg-slate-100
                                       hover:bg-slate-200
                                       text-slate-700
                                       rounded-xl
                                       text-xs font-bold
                                       transition"
                            >
                                Detail
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="5"
                            class="p-8
                                   text-center
                                   text-slate-400
                                   font-bold"
                        >
                            Tidak ada pemagang aktif saat ini.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </x-admin.table-card>



    {{-- =========================================================
        PISAHKAN DATA SELESAI DAN DITOLAK
        Dari collection $pemagangSelesai
        ========================================================= --}}
    @php

        $dataRiwayat = collect($pemagangSelesai ?? [])
            ->filter(function ($item) {
                return strtolower($item->status ?? '') === 'diterima';
            });

        $dataDitolak = collect($pemagangSelesai ?? [])
            ->filter(function ($item) {
                return strtolower($item->status ?? '') === 'ditolak';
            });

    @endphp



    {{-- =========================================================
        2. RIWAYAT / PEMAGANG SELESAI
        ========================================================= --}}
    <x-admin.table-card
        icon="📁"
        title="Riwayat / Pemagang Selesai"
        description="Arsip peserta yang telah diterima dan masa magangnya sudah selesai."
    >

        <table class="w-full text-left border-collapse min-w-[700px]">

            <thead>
                <tr
                    class="bg-slate-50
                           border-b border-slate-200
                           text-slate-600
                           font-bold text-xs
                           uppercase tracking-wider"
                >
                    <th class="p-4 px-6">Nama Pendaftar</th>
                    <th class="p-4 px-6">Asal Instansi / Sekolah</th>
                    <th class="p-4 px-6">Periode Magang</th>
                    <th class="p-4 px-6">Keterangan</th>
                    <th class="p-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody
                class="divide-y divide-slate-100
                       text-sm font-medium
                       text-slate-700"
            >

                @forelse($dataRiwayat as $item)

                    <tr class="hover:bg-slate-50/50 transition">

                        {{-- Nama --}}
                        <td class="p-4 px-6 font-bold text-slate-900">
                            {{ $item->nama_lengkap ?? '-' }}
                        </td>

                        {{-- Instansi --}}
                        <td class="p-4 px-6 text-slate-600">
                            {{ $item->instansi ?? $item->asal_sekolah ?? '-' }}
                        </td>

                        {{-- Periode --}}
                        <td class="p-4 px-6 text-xs text-slate-500">

                            @if($item->tanggal_mulai && $item->tanggal_selesai)

                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                -
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}

                            @else
                                -
                            @endif

                        </td>

                        {{-- Keterangan --}}
                        <td class="p-4 px-6">

                            <span
                                class="px-2.5 py-1
                                       rounded-full
                                       bg-slate-200
                                       text-slate-700
                                       text-xs font-bold"
                            >
                                Selesai Magang
                            </span>

                        </td>

                        {{-- Aksi --}}
                        <td class="p-4 px-6 text-center">

                            <a
                                href="{{ route('admin.pendaftar.show', $item->id) }}"
                                class="px-3 py-1.5
                                       bg-slate-100
                                       hover:bg-slate-200
                                       text-slate-700
                                       rounded-xl
                                       text-xs font-bold
                                       transition"
                            >
                                Detail
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="5"
                            class="p-8
                                   text-center
                                   text-slate-400
                                   font-bold"
                        >
                            Belum ada riwayat pemagang selesai.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </x-admin.table-card>



    {{-- =========================================================
        3. DAFTAR PEMAGANG DITOLAK
        ========================================================= --}}
    <x-admin.table-card
        icon="❌"
        title="Daftar Pemagang Ditolak"
        description="Arsip peserta yang pengajuan magang atau PKL-nya ditolak."
    >

        <table class="w-full text-left border-collapse min-w-[700px]">

            <thead>
                <tr
                    class="bg-slate-50
                           border-b border-slate-200
                           text-slate-600
                           font-bold text-xs
                           uppercase tracking-wider"
                >
                    <th class="p-4 px-6">Nama Pendaftar</th>
                    <th class="p-4 px-6">Asal Instansi / Sekolah</th>
                    <th class="p-4 px-6">Periode Magang</th>
                    <th class="p-4 px-6">Keterangan</th>
                    <th class="p-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody
                class="divide-y divide-slate-100
                       text-sm font-medium
                       text-slate-700"
            >

                @forelse($dataDitolak as $item)

                    <tr
                        class="hover:bg-rose-50/30
                               transition"
                    >

                        {{-- Nama --}}
                        <td class="p-4 px-6 font-bold text-slate-900">
                            {{ $item->nama_lengkap ?? '-' }}
                        </td>

                        {{-- Instansi --}}
                        <td class="p-4 px-6 text-slate-600">
                            {{ $item->instansi ?? $item->asal_sekolah ?? '-' }}
                        </td>

                        {{-- Periode --}}
                        <td class="p-4 px-6 text-xs text-slate-500">

                            @if($item->tanggal_mulai && $item->tanggal_selesai)

                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                -
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}

                            @else
                                -
                            @endif

                        </td>

                        {{-- Keterangan --}}
                        <td class="p-4 px-6">

                            <span
                                class="px-2.5 py-1
                                       rounded-full
                                       bg-rose-50
                                       text-rose-700
                                       text-xs font-bold"
                            >
                                Ditolak
                            </span>

                        </td>

                        {{-- Aksi --}}
                        <td class="p-4 px-6 text-center">

                            <a
                                href="{{ route('admin.pendaftar.show', $item->id) }}"
                                class="px-3 py-1.5
                                       bg-slate-100
                                       hover:bg-slate-200
                                       text-slate-700
                                       rounded-xl
                                       text-xs font-bold
                                       transition"
                            >
                                Detail
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="5"
                            class="p-8
                                   text-center
                                   text-slate-400
                                   font-bold"
                        >
                            Belum ada pendaftar yang ditolak.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </x-admin.table-card>

@endsection