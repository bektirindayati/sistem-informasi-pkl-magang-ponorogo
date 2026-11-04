@extends('admin.layout.app')

@section('page-title', 'Detail Pendaftar')

@section('content')
@php
    $tgl = fn ($nilai) => $nilai ? \Carbon\Carbon::parse($nilai)->translatedFormat('d M Y') : '-';

    $dataDiri = [
        'Nama Lengkap'      => $pendaftaran->nama_lengkap,
        'Kategori'          => $pendaftaran->kategori,
        'NIM / NISN'        => $pendaftaran->nim_nisn,
        'No. WhatsApp / HP' => $pendaftaran->no_hp,
        'Asal Instansi'     => $pendaftaran->instansi,
        'Jurusan'           => $pendaftaran->jurusan,
    ];

    $alamat = collect([$pendaftaran->alamat, $pendaftaran->kabupaten, $pendaftaran->provinsi])->filter()->implode(', ');

    $instansiPeriode = [
        'Dinas Tujuan'    => $pendaftaran->dinas_tujuan,
        'Bidang'          => $pendaftaran->divisi,
        'Tanggal Mulai'   => $tgl($pendaftaran->tanggal_mulai),
        'Tanggal Selesai' => $tgl($pendaftaran->tanggal_selesai),
    ];

    $kartu  = 'rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm dark:shadow-none p-6';
    $judul  = 'text-base font-extrabold text-slate-900 dark:text-white';
    $kecil  = 'text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500';
    $isi    = 'mt-1 text-sm font-semibold text-slate-800 dark:text-slate-100 break-words';
@endphp

<div class="space-y-6">

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 dark:border-emerald-500/25 bg-emerald-50 dark:bg-emerald-500/10 px-4 py-3 text-sm font-semibold text-emerald-700 dark:text-emerald-300">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="rounded-2xl border border-rose-200 dark:border-rose-500/25 bg-rose-50 dark:bg-rose-500/10 px-4 py-3 text-sm font-semibold text-rose-700 dark:text-rose-300">{{ session('error') }}</div>
    @endif

    <a href="{{ route('admin.pendaftar.index') }}"
       class="inline-block text-xs font-bold text-slate-500 hover:text-cyan-700 dark:text-slate-400 dark:hover:text-cyan-400 transition">
    Kembali ke Data Pendaftar
    </a>

    {{-- 1. STATUS VERIFIKASI (paling atas) --}}
    <x-admin.status-verifikasi :pendaftaran="$pendaftaran" />

    {{-- 2. SURAT BALASAN: status surat + detail surat (muncul setelah diterima) --}}
    <x-admin.surat-balasan :pendaftaran="$pendaftaran" />

     {{-- 3. Sertifikat: status sertif + detail sertif (muncul setelah diterima) --}}
    <x-admin.sertifikat :pendaftaran="$pendaftaran" />

    {{-- 4. DATA PENDAFTAR --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

        <div class="xl:col-span-2 space-y-6">

            <div class="{{ $kartu }}">
                <h3 class="{{ $judul }}">Data Diri &amp; Akademik</h3>
                <dl class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                    @foreach ($dataDiri as $nama => $nilai)
                        <div>
                            <dt class="{{ $kecil }}">{{ $nama }}</dt>
                            <dd class="{{ $isi }}">{{ $nilai ?: '-' }}</dd>
                        </div>
                    @endforeach
                    <div class="sm:col-span-2">
                        <dt class="{{ $kecil }}">Alamat</dt>
                        <dd class="{{ $isi }}">{{ $alamat ?: '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="{{ $kartu }}">
                <h3 class="{{ $judul }}">Instansi Tujuan &amp; Periode</h3>
                <dl class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
                    @foreach ($instansiPeriode as $nama => $nilai)
                        <div>
                            <dt class="{{ $kecil }}">{{ $nama }}</dt>
                            <dd class="{{ $isi }}">{{ $nilai ?: '-' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div class="{{ $kartu }}">
                <h3 class="{{ $judul }}">Berkas Terlampir</h3>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach (['surat_pengantar' => 'Surat Pengantar', 'proposal' => 'Proposal'] as $kolom => $nama)
                        @if (filled($pendaftaran->{$kolom}))
                            <a href="{{ asset('storage/' . $pendaftaran->{$kolom}) }}" target="_blank" rel="noopener"
                               class="px-4 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700
                                      dark:bg-blue-500/10 dark:hover:bg-blue-500/20 dark:text-blue-300
                                      text-xs font-bold transition">
                                {{ $nama }}
                            </a>
                        @else
                            <span class="px-4 py-2 rounded-xl bg-slate-100 text-slate-400
                                         dark:bg-slate-800 dark:text-slate-500 text-xs font-semibold">
                                Tidak ada {{ strtolower($nama) }}
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="{{ $kartu }} space-y-2 text-xs text-slate-500 dark:text-slate-400">
                <p>Diajukan: {{ $pendaftaran->created_at->translatedFormat('d M Y, H:i') }}</p>
                <p>Terakhir diperbarui: {{ $pendaftaran->updated_at->translatedFormat('d M Y, H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection