@extends('user.layout.app')

@section('title', 'Status Pendaftaran')

@section('content')

<div class="min-h-screen bg-slate-50 pb-14 px-4 sm:px-6">
    <div class="max-w-2xl mx-auto">

        <div class="flex items-center justify-between mb-5">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">Status Pendaftaran</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau progres verifikasi pengajuan magang/PKL kamu.</p>
            </div>
            <a href="{{ route('user.pendaftaran.riwayat') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 whitespace-nowrap">
                Lihat Riwayat &rarr;
            </a>
        </div>

        @if(session('success'))
            <x-alert type="success">✨ {{ session('success') }}</x-alert>
        @endif
        @if(session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif

        @if(!$pendaftaran)

            {{-- BELUM PERNAH MENDAFTAR --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center shadow-sm mt-4">
                <div class="text-4xl mb-3">📭</div>
                <h2 class="font-bold text-slate-800">Belum Ada Pendaftaran</h2>
                <p class="text-sm text-slate-500 mt-1 mb-5">Kamu belum pernah mengajukan pendaftaran magang atau PKL.</p>
                <a href="{{ route('user.pendaftaran.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/20 transition">
                    Daftar Sekarang &rarr;
                </a>
            </div>

        @else
            @php
                $status = $pendaftaran->status;
                // Posisi tiap status di alur 3 langkah: dikirim -> verifikasi -> keputusan
                $stepAktif = in_array($status, ['pending', 'revisi']) ? 2 : (in_array($status, ['diterima', 'ditolak', 'selesai']) ? 3 : 1);
            @endphp

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm mt-4 overflow-hidden">

                {{-- HEADER: NOMOR PENDAFTARAN --}}
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <span class="text-sm font-extrabold text-slate-800">Pendaftaran #{{ $nomorUrut }}</span>
                    <span class="text-xs text-slate-400">{{ $pendaftaran->created_at->translatedFormat('d M Y') }}</span>
                </div>

                {{-- STEPPER — disembunyikan untuk status revisi (pakai kartu sendiri di bawah) --}}
                @if($status !== 'revisi')
                <div class="px-6 py-6 border-b border-slate-100">
                    <div class="flex items-center">
                        {{-- Step 1: Data Dikirim --}}
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold">✓</div>
                            <span class="text-[11px] font-semibold text-slate-600 mt-2 text-center">Data<br>Dikirim</span>
                        </div>
                        <div class="flex-1 h-0.5 {{ $stepAktif >= 2 ? 'bg-emerald-500' : 'bg-slate-200' }} -mt-5"></div>

                        {{-- Step 2: Verifikasi --}}
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                                {{ $stepAktif > 2 ? 'bg-emerald-500 text-white' : ($stepAktif == 2 ? 'bg-blue-600 text-white animate-pulse' : 'bg-slate-200 text-slate-400') }}">
                                {{ $stepAktif > 2 ? '✓' : '●' }}
                            </div>
                            <span class="text-[11px] font-semibold text-slate-600 mt-2 text-center">Verifikasi</span>
                        </div>
                        <div class="flex-1 h-0.5 {{ $stepAktif >= 3 ? ($status == 'ditolak' ? 'bg-rose-400' : 'bg-emerald-500') : 'bg-slate-200' }} -mt-5"></div>

                        {{-- Step 3: Keputusan --}}
                        <div class="flex flex-col items-center flex-1">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                                {{ $status == 'diterima' || $status == 'selesai' ? 'bg-emerald-500 text-white' : ($status == 'ditolak' ? 'bg-rose-500 text-white' : 'bg-slate-200 text-slate-400') }}">
                                @if($status == 'diterima' || $status == 'selesai') ✓
                                @elseif($status == 'ditolak') ✕
                                @else ○
                                @endif
                            </div>
                            <span class="text-[11px] font-semibold text-slate-600 mt-2 text-center">Keputusan</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ISI SESUAI STATUS --}}
                <div class="p-6">

                    @if($status === 'pending')
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">⏳</div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">Sedang dalam proses verifikasi</h3>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Admin sedang memeriksa kelengkapan data dan dokumen kamu. Kamu akan diberi tahu begitu ada keputusan.</p>
                            </div>
                        </div>

                    @elseif($status === 'revisi')
                        <div class="p-4 bg-orange-50 border border-orange-200 rounded-2xl">
                            <div class="flex items-center gap-2 text-orange-700 font-extrabold text-sm mb-2">
                                ⚠️ Perlu Perbaikan
                            </div>
                            <p class="text-xs text-orange-900 mb-3">Silakan perbaiki data sesuai catatan admin di bawah ini.</p>
                            <div class="p-3 bg-white border border-orange-100 rounded-xl">
                                <p class="text-[11px] font-bold text-orange-700 uppercase tracking-wide mb-1">Alasan revisi:</p>
                                <p class="text-xs text-slate-700 leading-relaxed">"{{ $pendaftaran->alasan_revisi ?? 'Tidak ada catatan khusus.' }}"</p>
                            </div>
                            <a href="{{ route('user.pendaftaran.edit', $pendaftaran->id) }}" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                Perbaiki Pendaftaran &rarr;
                            </a>
                        </div>

                    @elseif($status === 'diterima')
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0">✅</div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">Selamat, pendaftaran kamu diterima!</h3>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Pengajuan magang/PKL kamu telah disetujui oleh instansi tujuan.</p>
                            </div>
                        </div>
                        @if($pendaftaran->surat_balasan ?? null)
                            <a href="{{ asset('storage/' . $pendaftaran->surat_balasan) }}" target="_blank" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                📄 Unduh Surat Balasan
                            </a>
                        @endif

                    @elseif($status === 'ditolak')
                        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl">
                            <div class="flex items-center gap-2 text-rose-700 font-extrabold text-sm mb-2">
                                ❌ Pendaftaran Ditolak
                            </div>
                            <div class="p-3 bg-white border border-rose-100 rounded-xl mb-4">
                                <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wide mb-1">Alasan penolakan:</p>
                                <p class="text-xs text-slate-700 leading-relaxed">"{{ $pendaftaran->alasan_penolakan ?? 'Tidak ada catatan khusus.' }}"</p>
                            </div>
                            <a href="{{ route('user.pendaftaran.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                                Daftar Magang Lagi &rarr;
                            </a>
                        </div>

                    @elseif($status === 'selesai')
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">🏁</div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">Magang telah selesai</h3>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Periode magang/PKL kamu di instansi ini sudah berakhir. Terima kasih atas kontribusinya!</p>
                            </div>
                        </div>

                    @elseif($status === 'draft')
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-lg shrink-0">📝</div>
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm">Masih draft, belum dikirim</h3>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">Lanjutkan mengisi formulir dan kirim pengajuan kalau sudah yakin datanya benar.</p>
                            </div>
                        </div>
                        <a href="{{ route('user.pendaftaran.edit', $pendaftaran->id) }}" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm transition">
                            Lanjutkan Draft &rarr;
                        </a>
                    @endif
                </div>

                {{-- INFO INSTANSI & PERIODE --}}
                @if(!in_array($status, ['draft']))
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <p class="text-xs text-slate-600"><span class="font-bold">Instansi:</span> {{ $pendaftaran->dinas_tujuan ?? '-' }} @if($pendaftaran->divisi ?? null) &middot; {{ $pendaftaran->divisi }} @endif</p>
                    @if($pendaftaran->tanggal_mulai && $pendaftaran->tanggal_selesai)
                        <p class="text-xs text-slate-500">
                            {{ \Carbon\Carbon::parse($pendaftaran->tanggal_mulai)->translatedFormat('d M Y') }}
                            &ndash;
                            {{ \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->translatedFormat('d M Y') }}
                        </p>
                    @endif
                </div>
                @endif

                {{-- AKSI TAMBAHAN (batalkan) — cuma untuk status yang boleh dibatalkan --}}
                @if(in_array($status, ['draft', 'pending', 'revisi', 'ditolak']))
                <div class="px-6 py-4 border-t border-slate-100">
                    <form action="{{ route('user.pendaftaran.destroy', $pendaftaran->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-slate-400 hover:text-rose-600 transition">
                            🗑️ Hapus/Batalkan Pengajuan
                        </button>
                    </form>
                </div>
                @endif

            </div>

            {{-- CATATAN: setelah dikirim (pending) data tidak bisa diedit --}}
            @if($status === 'pending')
                <p class="text-center text-[11px] text-slate-400 mt-4">
                    Data pendaftaran tidak dapat diubah selama masih dalam proses verifikasi.
                </p>
            @endif
        @endif

        <div class="mt-6 text-center">
            <a href="{{ route('user.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">&larr; Kembali ke Dashboard</a>
        </div>
    </div>
</div>
@endsection