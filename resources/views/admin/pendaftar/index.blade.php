@extends('admin.layout.app')

@section('page-title', 'Data Seluruh Pendaftar')

@section('content')

    @php
        // Kelas yang dipakai berulang di tiga tabel
        $thead  = 'bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 font-bold text-xs uppercase tracking-wider';
        $tbody  = 'divide-y divide-slate-100 dark:divide-slate-800 text-sm font-medium text-slate-700 dark:text-slate-300';
        $tr     = 'hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition';
        $nama   = 'p-4 px-6 font-bold text-slate-900 dark:text-slate-100';
        $asal   = 'p-4 px-6 text-slate-600 dark:text-slate-400';
        $periode= 'p-4 px-6 text-xs text-slate-500 dark:text-slate-400';
        $kosong = 'p-8 text-center text-slate-400 dark:text-slate-500 font-bold';
        $btnDetail = 'px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold transition';

        $badge = [
            'diterima' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300',
            'revisi'   => 'bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-300',
            'default'  => 'bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300',
        ];
    @endphp

    {{-- =========================================================
        PENCARIAN
        ========================================================= --}}
    <form method="GET"
          class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800
                 p-4 shadow-sm dark:shadow-none flex gap-3">
        <input type="text"
               name="search"
               value="{{ $search ?? '' }}"
               placeholder="Cari nama pendaftar..."
               class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-800
                      bg-white dark:bg-slate-950/60 text-sm text-slate-700 dark:text-slate-100
                      placeholder:text-slate-400 dark:placeholder:text-slate-500
                      focus:outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 dark:focus:ring-cyan-500/20">

        <button type="submit"
                class="px-5 py-2 bg-slate-800 hover:bg-slate-900 dark:bg-blue-600 dark:hover:bg-blue-500
                       text-white text-sm font-bold rounded-xl transition">
            Cari
        </button>

        @if($search ?? null)
            <a href="{{ route('admin.pendaftar.index') }}"
               class="px-4 py-2 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-100
                      text-sm font-bold transition">
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
                <tr class="{{ $thead }}">
                    <th class="p-4 px-6">Nama Pendaftar</th>
                    <th class="p-4 px-6">Asal Instansi / Sekolah</th>
                    <th class="p-4 px-6">Periode Magang</th>
                    <th class="p-4 px-6">Status</th>
                    <th class="p-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="{{ $tbody }}">
                @forelse($pemagangAktif ?? [] as $item)
                    @php $st = strtolower($item->status ?? 'pending'); @endphp

                    <tr class="{{ $tr }}">
                        <td class="{{ $nama }}">{{ $item->nama_lengkap ?? '-' }}</td>

                        <td class="{{ $asal }}">{{ $item->instansi ?? $item->asal_sekolah ?? '-' }}</td>

                        <td class="{{ $periode }}">
                            @if($item->tanggal_mulai && $item->tanggal_selesai)
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                -
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="p-4 px-6">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $badge[$st] ?? $badge['default'] }}">
                                {{ ucfirst($st) }}
                            </span>
                        </td>

                        <td class="p-4 px-6 text-center">
                            <a href="{{ route('admin.pendaftar.show', $item->id) }}" class="{{ $btnDetail }}">Detail</a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="{{ $kosong }}">Tidak ada pemagang aktif saat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>


    {{-- =========================================================
        PISAHKAN DATA SELESAI DAN DITOLAK
        ========================================================= --}}
    @php
    // Data pemagang yang sudah ditandai selesai oleh admin.
    $dataRiwayat = collect($pemagangSelesai ?? []);

    // Data pendaftar yang ditolak.
    $dataDitolak = collect($pemagangDitolak ?? []);
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
                <tr class="{{ $thead }}">
                    <th class="p-4 px-6">Nama Pendaftar</th>
                    <th class="p-4 px-6">Asal Instansi / Sekolah</th>
                    <th class="p-4 px-6">Periode Magang</th>
                    <th class="p-4 px-6">Keterangan</th>
                    <th class="p-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="{{ $tbody }}">
                @forelse($dataRiwayat as $item)
                    <tr class="{{ $tr }}">
                        <td class="{{ $nama }}">{{ $item->nama_lengkap ?? '-' }}</td>

                        <td class="{{ $asal }}">{{ $item->instansi ?? $item->asal_sekolah ?? '-' }}</td>

                        <td class="{{ $periode }}">
                            @if($item->tanggal_mulai && $item->tanggal_selesai)
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                -
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="p-4 px-6">
                            <span class="px-2.5 py-1 rounded-full bg-slate-200 text-slate-700
                                         dark:bg-slate-800 dark:text-slate-300 text-xs font-bold">
                                Selesai Magang
                            </span>
                        </td>

                        <td class="p-4 px-6 text-center">
                            <a href="{{ route('admin.pendaftar.show', $item->id) }}" class="{{ $btnDetail }}">Detail</a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="{{ $kosong }}">Belum ada riwayat pemagang selesai.</td>
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
                <tr class="{{ $thead }}">
                    <th class="p-4 px-6">Nama Pendaftar</th>
                    <th class="p-4 px-6">Asal Instansi / Sekolah</th>
                    <th class="p-4 px-6">Periode Magang</th>
                    <th class="p-4 px-6">Keterangan</th>
                    <th class="p-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="{{ $tbody }}">
                @forelse($dataDitolak as $item)
                    <tr class="hover:bg-rose-50/30 dark:hover:bg-rose-500/5 transition">
                        <td class="{{ $nama }}">{{ $item->nama_lengkap ?? '-' }}</td>

                        <td class="{{ $asal }}">{{ $item->instansi ?? $item->asal_sekolah ?? '-' }}</td>

                        <td class="{{ $periode }}">
                            @if($item->tanggal_mulai && $item->tanggal_selesai)
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                -
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                            @else
                                -
                            @endif
                        </td>

                        <td class="p-4 px-6">
                            <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-700
                                         dark:bg-rose-500/15 dark:text-rose-300 text-xs font-bold">
                                Ditolak
                            </span>
                        </td>

                        <td class="p-4 px-6 text-center">
                            <a href="{{ route('admin.pendaftar.show', $item->id) }}" class="{{ $btnDetail }}">Detail</a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="{{ $kosong }}">Belum ada pendaftar yang ditolak.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>

@endsection