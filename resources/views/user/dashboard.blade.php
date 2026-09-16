@extends('user.layout.app')

@section('title', 'Dashboard User')

@section('content')

<div class="min-h-screen bg-slate-50/50 pb-16">

    {{-- Hero Dashboard --}}
    {{--
        FIX: pt-6 sm:pt-8 di sini dihapus -- jarak dari navbar sekarang
        sudah diurus oleh <main class="pt-24 sm:pt-28"> di layout
        (navbar sudah mengambang, bukan menempel tepi lagi), jadi
        section ini cukup punya jarak samping saja.
    --}}
    <section class="px-4 sm:px-6">
        <div class="max-w-6xl mx-auto">

            <div class="relative overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-white via-blue-50/80 to-cyan-50 p-5 sm:p-10 shadow-sm">

                <div class="absolute -right-20 -top-24 w-72 h-72 rounded-full bg-blue-200/30 blur-3xl pointer-events-none"></div>
                <div class="absolute -left-20 -bottom-28 w-72 h-72 rounded-full bg-cyan-200/30 blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 sm:gap-6">

                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-blue-100 shadow-xs mb-3 sm:mb-4">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="text-[11px] sm:text-xs font-bold text-blue-700 tracking-wide uppercase">
                                Dashboard Pengguna
                            </span>
                        </div>

                        <h1 class="text-xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-800 leading-tight">
                            Selamat datang,
                            <span class="bg-gradient-to-r from-blue-700 via-cyan-600 to-sky-500 bg-clip-text text-transparent">
                                {{ $user->name }}
                            </span>
                        </h1>

                        <p class="mt-2.5 sm:mt-3 text-sm sm:text-base text-slate-600 leading-relaxed max-w-xl">
                            Kelola pendaftaran PKL / magang, pantau status pengajuan, dan temukan instansi tujuan yang tersedia dengan mudah.
                        </p>
                    </div>

                    {{-- Tombol Cepat Lengkapi Profil --}}
                    <div class="shrink-0">
                        <a href="{{ route('user.profil') }}"
                           class="group flex items-center justify-between gap-4 bg-white/90 hover:bg-white border border-blue-200/80 rounded-2xl p-4 shadow-xs hover:shadow-md hover:border-blue-300 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pengaturan Akun</p>
                                    <p class="text-sm font-bold text-slate-800">Lengkapi Profil Saya</p>
                                </div>
                            </div>
                            <div class="w-8 h-8 shrink-0 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    </div>

                </div>

            </div>

        </div>
    </section>

    {{-- Peringatan Lengkapi Profil --}}
    @php
        $profilBelumLengkap = empty($user->nim_nisn) || empty($user->instansi) || empty($user->jurusan);
    @endphp

    @if ($profilBelumLengkap)
        <section class="px-4 sm:px-6 mt-4 sm:mt-5">
            <div class="max-w-6xl mx-auto">
                <div class="relative overflow-hidden rounded-2xl sm:rounded-3xl border border-amber-200 bg-amber-50 p-4 sm:p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">

                        <div class="flex items-start sm:items-center gap-3 min-w-0">
                            <div class="w-10 h-10 shrink-0 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-amber-800">
                                    Lengkapi profil kamu terlebih dahulu
                                </p>
                                <p class="text-xs text-amber-700 mt-0.5 leading-relaxed">
                                    NIM/NISN, asal instansi, dan jurusan belum terisi. Lengkapi profil supaya data ini otomatis mengisi formulir pendaftaran magang nanti.
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('user.profil') }}"
                           class="shrink-0 w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition">
                            Lengkapi Sekarang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>

                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- Status + Aksi --}}
    <section class="px-4 sm:px-6 mt-5 sm:mt-6">
        <div class="max-w-6xl mx-auto">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

                {{-- Status Pendaftaran --}}
                <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-xs hover:shadow-md transition-shadow overflow-hidden flex flex-col justify-between">

                    @if (!$pendaftaran)
                        <div class="p-5 sm:p-7">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                            Status Pendaftaran
                                        </p>
                                    </div>
                                    <h2 class="text-lg sm:text-2xl font-extrabold text-slate-800">
                                        Belum Ada Pendaftaran
                                    </h2>
                                    <p class="mt-1.5 text-sm text-slate-500">
                                        Anda belum mengajukan pendaftaran magang.
                                    </p>
                                </div>
                                <div class="w-11 h-11 sm:w-12 sm:h-12 shrink-0 rounded-2xl bg-slate-50 border border-slate-100 text-slate-500 flex items-center justify-center">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-5 sm:mt-6 pt-4 sm:pt-5 border-t border-slate-100">
                                <p class="text-xs text-slate-400">Belum memiliki pengajuan?</p>
                                <p class="text-sm text-slate-600 mt-0.5">Mulai pendaftaran dan lengkapi data magang kamu.</p>
                            </div>
                        </div>
                    @else
                        @php
                            $status = strtolower($pendaftaran->status ?? 'draft');

                            $statusConfig = [
                                'draft' => [
                                    'label' => 'Draft',
                                    'description' => 'Pendaftaran belum dikirim dan masih dapat dilanjutkan.',
                                    'class' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    'dot' => 'bg-slate-500',
                                    'iconBg' => 'bg-slate-50',
                                    'iconColor' => 'text-slate-600',
                                ],
                                'pending' => [
                                    'label' => 'Menunggu Verifikasi',
                                    'description' => 'Pengajuan sedang diperiksa oleh admin.',
                                    'class' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'dot' => 'bg-amber-500',
                                    'iconBg' => 'bg-amber-50',
                                    'iconColor' => 'text-amber-600',
                                ],
                                'revisi' => [
                                    'label' => 'Perlu Revisi',
                                    'description' => 'Pengajuan perlu diperbaiki sebelum dapat diproses kembali.',
                                    'class' => 'bg-orange-50 text-orange-700 border-orange-200',
                                    'dot' => 'bg-orange-500',
                                    'iconBg' => 'bg-orange-50',
                                    'iconColor' => 'text-orange-600',
                                ],
                                'diterima' => [
                                    'label' => 'Diterima',
                                    'description' => 'Selamat! Pengajuan magang kamu telah diterima.',
                                    'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'dot' => 'bg-emerald-500',
                                    'iconBg' => 'bg-emerald-50',
                                    'iconColor' => 'text-emerald-600',
                                ],
                                'ditolak' => [
                                    'label' => 'Ditolak',
                                    'description' => 'Pengajuan sebelumnya tidak dapat dilanjutkan.',
                                    'class' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'dot' => 'bg-rose-500',
                                    'iconBg' => 'bg-rose-50',
                                    'iconColor' => 'text-rose-600',
                                ],
                                'selesai' => [
                                    'label' => 'Selesai',
                                    'description' => 'Masa magang pada pengajuan ini telah selesai.',
                                    'class' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'dot' => 'bg-blue-500',
                                    'iconBg' => 'bg-blue-50',
                                    'iconColor' => 'text-blue-600',
                                ],
                            ];

                            $config = $statusConfig[$status] ?? $statusConfig['draft'];
                        @endphp

                        <div class="p-5 sm:p-7 flex flex-col justify-between h-full">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                            Status Pendaftaran
                                        </p>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border text-[10px] font-bold {{ $config['class'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
                                            {{ $config['label'] }}
                                        </span>
                                    </div>

                                    <h2 class="text-lg sm:text-2xl font-extrabold text-slate-800">
                                        {{ $config['label'] }}
                                    </h2>

                                    <p class="mt-1.5 text-sm text-slate-500">
                                        {{ $pendaftaran->dinas_tujuan ?? 'Instansi belum dipilih' }}
                                    </p>
                                </div>

                                <div class="w-11 h-11 sm:w-12 sm:h-12 shrink-0 rounded-2xl {{ $config['iconBg'] }} {{ $config['iconColor'] }} flex items-center justify-center">
                                    @if ($status === 'diterima')
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @elseif ($status === 'ditolak')
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    @elseif ($status === 'pending')
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                                        </svg>
                                    @elseif ($status === 'revisi')
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L8.25 18.462 4 19.5l1.038-4.25L16.862 3.487z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-5 sm:mt-6 pt-4 sm:pt-5 border-t border-slate-100">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                                    <div>
                                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">
                                            Periode Magang
                                        </p>
                                        <p class="text-sm font-bold text-slate-700 mt-1">
                                            @if ($pendaftaran->tanggal_mulai && $pendaftaran->tanggal_selesai)
                                                {{ \Carbon\Carbon::parse($pendaftaran->tanggal_mulai)->translatedFormat('d M Y') }}
                                                <span class="text-slate-300 mx-1">—</span>
                                                {{ \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->translatedFormat('d M Y') }}
                                            @else
                                                Belum ditentukan
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-400 mt-1">
                                            {{ $config['description'] }}
                                        </p>
                                    </div>

                                    <a href="{{ route('user.pendaftaran.status') }}"
                                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-200 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-700 text-slate-700 text-xs font-bold transition">
                                        Lihat Status
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                {{-- Aksi Utama --}}
                <div class="relative overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-50 via-white to-cyan-50 p-5 sm:p-7 shadow-xs flex flex-col justify-between">
                    <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full bg-blue-200/30 blur-2xl"></div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <p class="text-[11px] font-bold uppercase tracking-wider text-blue-700">
                                Aksi Utama
                            </p>
                        </div>

                        <h2 class="mt-2 text-lg sm:text-xl font-extrabold text-slate-800">
                            Mulai dari sini
                        </h2>

                        <p class="mt-1 text-sm text-slate-500 leading-relaxed">
                            Akses fitur utama pendaftaran dengan cepat.
                        </p>

                        <div class="mt-4 sm:mt-5">
                            @if (!$pendaftaran)
                                <a href="{{ route('user.pendaftaran.create') }}"
                                   class="group w-full flex items-center justify-between gap-3 bg-white border border-blue-100 hover:border-blue-300 hover:shadow-md rounded-xl px-4 py-3.5 transition">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Daftar Magang</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Buat pengajuan baru</p>
                                    </div>
                                    <div class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center group-hover:translate-x-0.5 transition shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </div>
                                </a>
                            @elseif ($pendaftaran->status === 'draft')
                                <a href="{{ route('user.pendaftaran.edit', $pendaftaran->id) }}"
                                   class="group w-full flex items-center justify-between gap-3 bg-white border border-amber-100 hover:border-amber-300 hover:shadow-md rounded-xl px-4 py-3.5 transition">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Lanjutkan Draft</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Lengkapi dan kirim pengajuan</p>
                                    </div>
                                    <div class="w-9 h-9 rounded-lg bg-amber-500 text-white flex items-center justify-center group-hover:translate-x-0.5 transition shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </div>
                                </a>
                            @elseif ($pendaftaran->status === 'revisi')
                                <a href="{{ route('user.pendaftaran.edit', $pendaftaran->id) }}"
                                   class="group w-full flex items-center justify-between gap-3 bg-white border border-orange-100 hover:border-orange-300 hover:shadow-md rounded-xl px-4 py-3.5 transition">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Perbaiki Pengajuan</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Lengkapi revisi dari admin</p>
                                    </div>
                                    <div class="w-9 h-9 rounded-lg bg-orange-500 text-white flex items-center justify-center group-hover:translate-x-0.5 transition shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </div>
                                </a>
                            @elseif (in_array($pendaftaran->status, ['ditolak', 'selesai']))
                                <a href="{{ route('user.pendaftaran.create') }}"
                                   class="group w-full flex items-center justify-between gap-3 bg-white border border-blue-100 hover:border-blue-300 hover:shadow-md rounded-xl px-4 py-3.5 transition">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Daftar Magang Lagi</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Buat pengajuan baru</p>
                                    </div>
                                    <div class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center group-hover:translate-x-0.5 transition shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </div>
                                </a>
                            @else
                                <a href="{{ route('user.pendaftaran.status') }}"
                                   class="group w-full flex items-center justify-between gap-3 bg-white border border-blue-100 hover:border-blue-300 hover:shadow-md rounded-xl px-4 py-3.5 transition">
                                    <div>
                                        <p class="text-sm font-bold text-slate-800">Cek Pengajuan</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Lihat perkembangan pengajuan</p>
                                    </div>
                                    <div class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center group-hover:translate-x-0.5 transition shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>


    {{-- Menu Cepat --}}
    <section class="px-4 sm:px-6 mt-8 sm:mt-10">
        <div class="max-w-6xl mx-auto">

            <div class="flex items-end justify-between gap-4 mb-4 sm:mb-5">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600">
                        Navigasi
                    </p>
                    <h2 class="text-lg sm:text-2xl font-extrabold text-slate-800 mt-1">
                        Menu Cepat
                    </h2>
                </div>
                <span class="hidden sm:block text-xs text-slate-400">
                    Akses fitur SiMagang
                </span>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

                {{-- Daftar --}}
                <a href="{{ route('user.pendaftaran.create') }}"
                   class="group relative bg-white border border-slate-200 rounded-2xl sm:rounded-3xl p-4 sm:p-5 hover:border-blue-300 hover:shadow-lg hover:shadow-blue-100/60 transition overflow-hidden">
                    <div class="absolute right-0 top-0 w-16 h-16 bg-blue-50 rounded-bl-[40px] opacity-60"></div>
                    <div class="relative">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3 sm:mb-4">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-sm text-slate-800">Daftar Magang</h3>
                        <p class="text-[11px] sm:text-xs text-slate-500 mt-1 leading-relaxed">
                            Ajukan pendaftaran baru.
                        </p>
                        <div class="mt-3 text-blue-600 opacity-0 group-hover:opacity-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Status --}}
                <a href="{{ route('user.pendaftaran.status') }}"
                   class="group relative bg-white border border-slate-200 rounded-2xl sm:rounded-3xl p-4 sm:p-5 hover:border-cyan-300 hover:shadow-lg hover:shadow-cyan-100/60 transition overflow-hidden">
                    <div class="absolute right-0 top-0 w-16 h-16 bg-cyan-50 rounded-bl-[40px] opacity-60"></div>
                    <div class="relative">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center mb-3 sm:mb-4">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-sm text-slate-800">Status Pengajuan</h3>
                        <p class="text-[11px] sm:text-xs text-slate-500 mt-1 leading-relaxed">
                            Pantau proses pendaftaran.
                        </p>
                        <div class="mt-3 text-cyan-600 opacity-0 group-hover:opacity-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Informasi --}}
                <a href="{{ route('informasi') }}"
                   class="group relative bg-white border border-slate-200 rounded-2xl sm:rounded-3xl p-4 sm:p-5 hover:border-sky-300 hover:shadow-lg hover:shadow-sky-100/60 transition overflow-hidden">
                    <div class="absolute right-0 top-0 w-16 h-16 bg-sky-50 rounded-bl-[40px] opacity-60"></div>
                    <div class="relative">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center mb-3 sm:mb-4">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-sm text-slate-800">Informasi Magang</h3>
                        <p class="text-[11px] sm:text-xs text-slate-500 mt-1 leading-relaxed">
                            Lihat panduan dan ketentuan.
                        </p>
                        <div class="mt-3 text-sky-600 opacity-0 group-hover:opacity-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>

                {{-- Instansi --}}
                <a href="#instansi"
                   class="group relative bg-white border border-slate-200 rounded-2xl sm:rounded-3xl p-4 sm:p-5 hover:border-blue-300 hover:shadow-lg hover:shadow-blue-100/60 transition overflow-hidden">
                    <div class="absolute right-0 top-0 w-16 h-16 bg-blue-50 rounded-bl-[40px] opacity-60"></div>
                    <div class="relative">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-3 sm:mb-4">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-sm text-slate-800">Instansi Tersedia</h3>
                        <p class="text-[11px] sm:text-xs text-slate-500 mt-1 leading-relaxed">
                            Lihat pilihan instansi magang.
                        </p>
                        <div class="mt-3 text-blue-600 opacity-0 group-hover:opacity-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>

            </div>

        </div>
    </section>


    {{-- Instansi Tersedia --}}
    <section id="instansi" class="px-4 sm:px-6 mt-8 sm:mt-12">
        <div class="max-w-6xl mx-auto">

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-4 sm:mb-5">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-blue-600">
                        Instansi Tujuan
                    </p>
                    <h2 class="text-lg sm:text-2xl font-extrabold text-slate-800 mt-1">
                        Instansi yang Tersedia
                    </h2>
                    <p class="text-sm text-slate-500 mt-1">
                        Pilihan instansi yang saat ini membuka pendaftaran melalui sistem.
                    </p>
                </div>

                <div class="inline-flex items-center self-start sm:self-auto gap-2 px-3 py-1.5 rounded-full bg-white border border-slate-200 text-xs font-semibold text-slate-500">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    {{ $dinases->count() }} instansi aktif
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                @forelse ($dinases as $dinas)
                    <div class="group bg-white border border-slate-200 rounded-2xl sm:rounded-3xl p-4 sm:p-5 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-100/50 transition">
                        <div class="flex items-start gap-3 sm:gap-4">
                            <div class="w-10 h-10 sm:w-11 sm:h-11 shrink-0 rounded-xl sm:rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M12 9h.01M15 9h.01"/>
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-sm text-slate-800 leading-snug group-hover:text-blue-700 transition">
                                    {{ $dinas->nama_dinas }}
                                </h3>

                                @if ($dinas->deskripsi)
                                    <p class="text-xs text-slate-500 mt-1.5 leading-relaxed line-clamp-2">
                                        {{ $dinas->deskripsi }}
                                    </p>
                                @else
                                    <p class="text-xs text-slate-400 mt-1.5">
                                        Informasi instansi tersedia melalui sistem.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white border border-dashed border-slate-300 rounded-3xl p-10 text-center">
                        <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-700">
                            Belum ada instansi tersedia
                        </h3>
                        <p class="text-sm text-slate-500 mt-1">
                            Silakan cek kembali nanti.
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </section>

</div>

@endsection