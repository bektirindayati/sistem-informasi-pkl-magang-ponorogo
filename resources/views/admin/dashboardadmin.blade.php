
@extends('admin.layout.app')

@section('page-title', 'Dashboard Admin')

@section('content')

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-3xl p-6 md:p-8
                text-white shadow-lg dark:shadow-none
                bg-gradient-to-br from-blue-600 via-blue-700 to-cyan-700
                dark:bg-none dark:bg-slate-900 dark:border dark:border-slate-800
                flex flex-col md:flex-row justify-between items-center gap-6">

        <div class="absolute -right-12 -top-12 w-56 h-56 rounded-full
                    bg-white/10 dark:bg-cyan-500/10 blur-3xl pointer-events-none"></div>

        <div class="absolute -bottom-20 -left-10 w-48 h-48 rounded-full
                    bg-cyan-400/20 dark:bg-blue-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 max-w-xl">
            <span class="px-3 py-1 rounded-full
                         bg-white/20 text-white
                         dark:bg-slate-800 dark:text-cyan-300
                         backdrop-blur-md text-[10px] md:text-xs
                         font-bold uppercase tracking-wider mb-3 inline-block">
                Panel Kontrol Utama
            </span>

            <h2 class="text-xl md:text-3xl font-extrabold tracking-tight mb-2
                       text-white dark:text-white">
                Selamat Datang, Administrator!
            </h2>

            <p class="text-blue-100 dark:text-slate-400
                      text-xs md:text-sm font-medium leading-relaxed">
                Kelola data pengajuan magang dan PKL mahasiswa/siswa
                dengan cepat dan transparan melalui sistem terintegrasi.
            </p>
        </div>
    </div>

  {{-- Pengingat --}}
@php
    $pengingat = array_filter([
        'surat balasan belum diterbitkan'                => $suratMenunggu ?? 0,
        'masa magang berakhir, siap ditandai selesai'    => $perluSelesai ?? 0,
    ]);
@endphp

@if ($pengingat)
    <div class="rounded-2xl border border-amber-200 dark:border-amber-500/25 bg-amber-50 dark:bg-amber-500/10
                px-5 py-4 text-base text-amber-800 dark:text-amber-200 space-y-1">
        @foreach ($pengingat as $teks => $jumlah)
            <p><span class="font-bold">{{ $jumlah }}</span> {{ $teks }}</p>
        @endforeach
    </div>
@endif

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
    <x-admin.stat-card icon="📄" color="cyan"    label="Total Pengajuan" :value="$totalPengajuan ?? 0" />
    <x-admin.stat-card icon="👥" color="blue"    label="Total Peserta"   :value="$totalPeserta ?? 0" />
    <x-admin.stat-card icon="✅" color="emerald" label="Diterima"        :value="$totalDiterima ?? 0" />
    <x-admin.stat-card icon="⏳" color="amber"   label="Menunggu Review" :value="$totalPending ?? 0" />
</div>

    {{-- Tabel Pengajuan Terbaru --}}
    <div class="bg-white dark:bg-[#111b32]
                rounded-3xl border border-blue-100 dark:border-slate-700
                shadow-sm dark:shadow-none overflow-hidden">

        {{-- Table Header --}}
        <div class="p-5 md:p-6
                    border-b border-blue-100 dark:border-slate-700
                    flex items-center justify-between gap-4">

            <div>
                <h3 class="font-extrabold text-slate-900 dark:text-white
                           text-base md:text-lg">
                    Daftar Pengajuan Masuk
                </h3>

                <p class="text-xs text-slate-600 dark:text-slate-400
                          font-medium mt-0.5">
                    Daftar mahasiswa/siswa yang baru saja mengirimkan berkas.
                </p>
            </div>

            <a href="{{ route('admin.pendaftar.index') }}"
               class="text-xs font-bold
                      text-blue-700 hover:text-blue-800
                      dark:text-cyan-400 dark:hover:text-cyan-300
                      transition shrink-0">
                Lihat Semua →
            </a>
        </div>

        {{-- Table Content --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">

                <thead>
                    <tr class="bg-blue-50/70 dark:bg-slate-950/60
                               border-b border-blue-100 dark:border-slate-700
                               text-slate-600 dark:text-slate-400
                               font-bold text-xs uppercase tracking-wider">

                        <th class="p-4 px-6">Nama Pendaftar</th>
                        <th class="p-4 px-6">Asal Instansi / Sekolah</th>
                        <th class="p-4 px-6">Kategori</th>
                        <th class="p-4 px-6">Bidang Tujuan</th>
                        <th class="p-4 px-6">Status</th>
                        <th class="p-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-blue-50 dark:divide-slate-800
                              text-sm text-slate-700 dark:text-slate-300">

                    @php
                        $badgeStatus = [
                            'diterima' => 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-500/15 dark:text-emerald-300 dark:border-emerald-500/30',
                            'ditolak'  => 'bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-500/15 dark:text-rose-300 dark:border-rose-500/30',
                            'pending'  => 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-500/15 dark:text-amber-300 dark:border-amber-500/30',
                            'revisi'   => 'bg-orange-50 text-orange-700 border border-orange-200 dark:bg-orange-500/15 dark:text-orange-300 dark:border-orange-500/30',
                            'selesai'  => 'bg-cyan-50 text-cyan-700 border border-cyan-200 dark:bg-cyan-500/15 dark:text-cyan-300 dark:border-cyan-500/30',
                            'draft'    => 'bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
                        ];
                    @endphp

                    @forelse($pendaftarTerbaru ?? [] as $item)
                        @php
                            $status = strtolower($item->status ?? 'pending');
                            $bidangTujuan = $item->bidang?->nama_bidang ?? $item->divisi ?? '-';
                        @endphp

                        <tr class="hover:bg-blue-50/50 dark:hover:bg-slate-800/50
                                   transition duration-150">

                            <td class="p-4 px-6 font-bold
                                text-slate-900 dark:text-slate-100">
                            {{ $item->nama_lengkap ?? $item->nama ?? '-' }}

                            @if ($item->tipe === 'kelompok')
                                <p class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400">
                                    Kelompok · {{ $item->anggotas_count }} anggota
                                </p>
                            @endif
                        </td>

                            <td class="p-4 px-6 text-slate-600 dark:text-slate-400
                                       font-medium">
                                {{ $item->asal_instansi ?? $item->instansi ?? $item->asal_sekolah ?? '-' }}
                            </td>

                            <td class="p-4 px-6">
                                <span class="px-3 py-1 rounded-full
                                             bg-slate-100 text-slate-700
                                             border border-slate-200
                                             dark:bg-slate-800 dark:text-slate-300
                                             dark:border-slate-700
                                             text-xs font-bold">
                                   {{ \App\Models\PendaftaranMagang::TIPE[$item->tipe] ?? ($item->kategori ?? 'Mahasiswa/Siswa') }}
                                </span>
                            </td>

                            <td class="p-4 px-6 max-w-xs">
                                <span class="inline-block px-3 py-1 rounded-lg
                                             bg-blue-50 text-blue-700
                                             border border-blue-100
                                             dark:bg-blue-500/10 dark:text-cyan-300
                                             dark:border-blue-500/20
                                             text-xs font-bold truncate max-w-full"
                                      title="{{ $bidangTujuan }}">
                                    {{ $bidangTujuan }}
                                </span>
                            </td>

                            <td class="p-4 px-6">
                                <span class="px-3 py-1 rounded-full
                                             text-xs font-extrabold uppercase tracking-wide
                                             {{ $badgeStatus[$status] ?? $badgeStatus['draft'] }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>

                            <td class="p-4 px-6 text-center">
    <div class="flex items-center justify-center gap-2">

        {{-- Tombol Detail --}}
        <a href="{{ route('admin.pendaftar.show', $item->id) }}"
           class="px-3.5 py-1.5
                  bg-slate-100 hover:bg-blue-100
                  text-slate-700 hover:text-blue-800
                  border border-slate-200 hover:border-blue-200
                  dark:bg-slate-800 dark:hover:bg-slate-700
                  dark:text-slate-200 dark:border-slate-700
                  rounded-xl text-xs font-bold transition">
            Detail
        </a>

        {{-- Aksi berdasarkan status --}}
        @if ($status === 'diterima')
            <a href="{{ route('admin.surat.show', $item->id) }}"
               target="_blank"
               rel="noopener"
               class="px-3.5 py-1.5
                      bg-emerald-600 hover:bg-emerald-700
                      text-white rounded-xl text-xs font-bold
                      shadow-sm dark:shadow-none transition">
                Lihat Surat
            </a>

        
@elseif ($status === 'selesai')
    @if ($item->sertifikatTerbit())
        <a href="{{ route('admin.pendaftar.show', $item->id) }}"
           class="px-3.5 py-1.5
                  bg-cyan-600 hover:bg-cyan-700
                  text-white rounded-xl text-xs font-bold
                  shadow-sm dark:shadow-none transition">
            Lihat Sertifikat
        </a>
    @else
        <a href="{{ route('admin.pendaftar.show', $item->id) }}"
           class="px-3.5 py-1.5
                  bg-cyan-600 hover:bg-cyan-700
                  text-white rounded-xl text-xs font-bold
                  shadow-sm dark:shadow-none transition">
            Proses Sertifikat
        </a>
    @endif

@elseif (in_array($status, ['pending', 'revisi'], true))
    <a href="{{ route('admin.verifikasi.index') }}"
       class="px-3.5 py-1.5
              bg-amber-500 hover:bg-amber-600
              text-white dark:text-slate-950
              rounded-xl text-xs font-bold
              shadow-sm dark:shadow-none transition">
        Review
    </a>
@endif

</div>

</td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6"
                                class="p-10 text-center
                                       text-slate-500 dark:text-slate-500
                                       font-semibold">
                                Belum ada data pengajuan magang atau PKL yang masuk ke Dinas Kominfo.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

@endsection
