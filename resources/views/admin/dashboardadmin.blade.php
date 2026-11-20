@extends('admin.layout.app')

@section('page-title', 'Dashboard Admin')

@section('content')
@php
    $badge = [
        'pending'  => 'bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300',
        'diterima' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300',
        'revisi'   => 'bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-300',
        'ditolak'  => 'bg-rose-50 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300',
        'selesai'  => 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
    ];
    $label = [
        'pending' => 'Menunggu Verifikasi', 'revisi' => 'Perlu Revisi', 'diterima' => 'Diterima',
        'ditolak' => 'Ditolak', 'selesai' => 'Selesai Magang',
    ];
@endphp

    {{-- BANNER --}}
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8 text-white shadow-lg dark:shadow-none
                bg-gradient-to-br from-blue-600 via-blue-700 to-cyan-700
                dark:bg-none dark:bg-slate-900 dark:border dark:border-slate-800">

        <div class="absolute -right-12 -top-12 w-56 h-56 rounded-full bg-white/10 dark:bg-cyan-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-10 w-48 h-48 rounded-full bg-cyan-400/20 dark:bg-blue-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-2xl">
            <span class="px-3 py-1 rounded-full bg-white/20 text-white dark:bg-slate-800 dark:text-cyan-300
                         backdrop-blur-md text-[10px] md:text-xs font-bold uppercase tracking-wider mb-3 inline-block">
                {{ $lingkup }}
            </span>

            <h2 class="text-xl md:text-3xl font-extrabold tracking-tight mb-2 text-white">Selamat Datang, Administrator</h2>

            <p class="text-blue-100 dark:text-slate-400 text-xs md:text-sm font-medium leading-relaxed">
                Kelola data pengajuan magang dan PKL mahasiswa/siswa dengan cepat dan transparan melalui sistem terintegrasi.
            </p>
        </div>
    </div>

    {{-- PERLU TINDAKAN --}}
    <div>
        <p class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Perlu Tindakan</p>
        <h3 class="mt-1 mb-4 text-xl font-extrabold text-slate-900 dark:text-white">Ringkasan pekerjaan admin</h3>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-admin.stat-card icon="clock" color="amber" label="Menunggu Review" :value="$menunggu"
                               catatan="Pengajuan baru dan revisi"
                               :href="route('admin.pendaftar.index', ['tampil' => 'menunggu'])" />

            <x-admin.stat-card icon="document" color="blue" label="Surat Balasan Belum Terbit" :value="$surat"
                               catatan="Pendaftar yang sudah diterima"
                               :href="route('admin.pendaftar.index', ['tampil' => 'surat'])" />

            <x-admin.stat-card icon="cap" color="cyan" label="Selesai, Sertifikat Belum Terbit" :value="$sertifikat"
                               catatan="Magang berakhir, sertifikat belum diterbitkan"
                               :href="route('admin.pendaftar.index', ['tampil' => 'sertifikat'])" />
        </div>
    </div>
{{-- DAFTAR PENGAJUAN MASUK --}}
<x-admin.table-card title="Daftar Pengajuan Masuk" description="Mahasiswa/siswa yang baru saja mengirimkan berkas.">
    <x-slot:actions>
        <a href="{{ route('admin.pendaftar.index') }}"
           class="text-sm font-semibold text-blue-700 hover:text-blue-800 dark:text-cyan-400 dark:hover:text-cyan-300 transition">
            Lihat Semua
        </a>
    </x-slot:actions>

    <table class="w-full text-left border-collapse min-w-[760px]">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800
                       text-slate-700 dark:text-slate-200 font-bold text-sm uppercase tracking-wide">
                <th class="p-4 px-6">Nama Pendaftar</th>
                <th class="p-4 px-6">Asal Instansi / Sekolah</th>
                <th class="p-4 px-6">Kategori</th>
                <th class="p-4 px-6">Bidang Tujuan</th>
                <th class="p-4 px-6">Status</th>
                <th class="p-4 px-6 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-base text-slate-600 dark:text-slate-300">
            @forelse ($pendaftarTerbaru as $item)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                    <td class="p-4 px-6">
                        <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->nama_lengkap ?? '-' }}</span>
                        @if ($item->tipe === 'kelompok')
                            <div class="mt-1"><x-admin.badge-kelompok :pendaftaran="$item" /></div>
                        @endif
                    </td>

                    <td class="p-4 px-6">{{ $item->instansi ?? '-' }}</td>

                    <td class="p-4 px-6">{{ $item->kategori ?? '-' }}</td>

                    <td class="p-4 px-6">{{ $item->bidang?->nama_bidang ?? $item->divisi ?? '-' }}</td>

                    <td class="p-4 px-6">
                        <span class="px-2.5 py-1 rounded-full text-sm font-semibold whitespace-nowrap {{ $badge[$item->status] ?? $badge['pending'] }}">
                            {{ $label[$item->status] ?? ucfirst($item->status) }}
                        </span>
                    </td>

                    <td class="p-4 px-6">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.pendaftar.show', $item->id) }}"
                               class="px-3 py-1.5 rounded-lg text-sm font-semibold transition
                                      bg-slate-100 hover:bg-slate-200 text-slate-700
                                      dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200">Detail</a>

                            @if (in_array($item->status, ['pending', 'revisi'], true))
                                <a href="{{ route('admin.verifikasi.index') }}"
                                   class="px-3 py-1.5 rounded-lg text-sm font-semibold text-white bg-amber-500 hover:bg-amber-600 transition">Review</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-10 text-center text-slate-400 dark:text-slate-500 font-semibold">
                        Belum ada pengajuan magang atau PKL yang masuk.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-admin.table-card>
@endsection