{{-- resources/views/user/pendaftaran/riwayat.blade.php --}}

@extends('user.layout.app')

@section('title', 'Riwayat Pendaftaran')

@section('content')

<div class="min-h-screen bg-slate-50 pb-14 px-4 sm:px-6">

    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">

            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                Riwayat Pendaftaran
            </h1>

            <p class="text-xs sm:text-sm text-slate-500 mt-1 leading-relaxed">
                Semua pengajuan magang/PKL yang pernah kamu kirim, termasuk pengajuan sebelumnya.
            </p>

        </div>


        {{-- Daftar Riwayat --}}
        <div class="space-y-3">

            @forelse($riwayat as $index => $item)

                @php
                    $badge = [
                        'draft' => [
                            'label' => 'Draft',
                            'class' => 'bg-slate-100 text-slate-600 border-slate-200'
                        ],
                        'pending' => [
                            'label' => 'Menunggu Verifikasi',
                            'class' => 'bg-blue-50 text-blue-700 border-blue-100'
                        ],
                        'revisi' => [
                            'label' => 'Perlu Revisi',
                            'class' => 'bg-orange-50 text-orange-700 border-orange-100'
                        ],
                        'diterima' => [
                            'label' => 'Diterima',
                            'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100'
                        ],
                        'ditolak' => [
                            'label' => 'Ditolak',
                            'class' => 'bg-rose-50 text-rose-700 border-rose-100'
                        ],
                        'selesai' => [
                            'label' => 'Selesai',
                            'class' => 'bg-indigo-50 text-indigo-700 border-indigo-100'
                        ],
                    ][$item->status] ?? [
                        'label' => ucfirst($item->status),
                        'class' => 'bg-slate-100 text-slate-600 border-slate-200'
                    ];
                @endphp


                <div class="bg-white border border-slate-200 rounded-2xl px-5 py-4 shadow-sm hover:shadow-md hover:border-slate-300 transition">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        {{-- Informasi Pengajuan --}}
                        <div class="min-w-0">

                            <div class="flex items-center gap-2 flex-wrap">

                                <span class="text-sm font-bold text-slate-800">
                                    Pendaftaran #{{ $riwayat->count() - $index }}
                                </span>

                                <span class="inline-flex items-center px-2.5 py-1 rounded-full border text-[10px] font-bold uppercase tracking-wide {{ $badge['class'] }}">
                                    {{ $badge['label'] }}
                                </span>

                            </div>


                            <div class="mt-2">

                                <p class="text-sm text-slate-600 truncate">
                                    {{ $item->dinas_tujuan ?? 'Instansi belum dipilih' }}

                                    @if($item->divisi)
                                        <span class="text-slate-300 mx-1">•</span>
                                        {{ $item->divisi }}
                                    @endif
                                </p>

                                <p class="text-[11px] text-slate-400 mt-1">
                                    Diajukan {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                                </p>

                            </div>

                        </div>


                        {{-- Aksi --}}
                        @if($item->id === optional($riwayat->first())->id)

                            <a href="{{ route('user.pendaftaran.status') }}"
                               class="shrink-0 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-blue-50 text-blue-700 text-xs font-bold hover:bg-blue-100 transition">

                                Lihat Status

                                <span class="text-sm">
                                    →
                                </span>

                            </a>

                        @endif

                    </div>

                </div>

            @empty

                <div class="bg-white border border-dashed border-slate-300 rounded-2xl px-6 py-12 text-center">

                    <div class="w-12 h-12 mx-auto rounded-xl bg-blue-50 flex items-center justify-center text-2xl mb-4">
                        📭
                    </div>

                    <h3 class="text-sm font-bold text-slate-800">
                        Belum Ada Riwayat Pendaftaran
                    </h3>

                    <p class="text-xs sm:text-sm text-slate-500 mt-1">
                        Pengajuan yang pernah kamu kirim akan muncul di halaman ini.
                    </p>

                    <a href="{{ route('user.pendaftaran.create') }}"
                       class="inline-flex items-center gap-2 mt-5 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-sm">

                        Daftar Magang

                        <span>
                            →
                        </span>
                    </a>
                </div>

            @endforelse

        </div>

        {{--
            FIX: link ini sebelumnya nyempil DI DALAM blok @empty, jadi
            cuma muncul kalau riwayatnya kosong -- begitu ada 1 riwayat
            saja, link ini hilang. Sekarang dipindah keluar dari
            @forelse/@empty, selalu tampil di bawah, konsisten dengan
            pola "Kembali ke Dashboard" di halaman Status & Profil.
        --}}
        <div class="mt-6 text-center">
            <a href="{{ route('user.dashboard') }}"
               class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>

    </div>

</div>

@endsection