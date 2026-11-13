
{{-- resources/views/user/pendaftaran/riwayat.blade.php --}}

@extends('user.layout.app')

@section('title', 'Riwayat Pendaftaran')

@section('content')
<x-page>

    <x-page-header
        eyebrow="Pendaftaran"
        title="Riwayat Pendaftaran"
    >
        Semua pengajuan magang/PKL yang pernah kamu kirim, termasuk pengajuan sebelumnya.
    </x-page-header>

    <div class="space-y-4">

        @forelse($riwayat as $index => $item)

            <x-card
                :pad="false"
                class="!bg-white dark:!bg-[#111b32]
                       !border-slate-200 dark:!border-slate-700
                       px-5 py-4 sm:px-6
                       hover:!border-blue-300
                       dark:hover:!border-cyan-500/50
                       transition-all duration-200"
            >
                <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto_auto]
                            sm:items-center gap-x-8 gap-y-4">

                    {{-- INFORMASI PENGAJUAN --}}
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-bold text-slate-900 dark:text-white">
                                Pendaftaran #{{ $riwayat->count() - $index }}
                            </span>

                            <x-status-badge :status="$item->status" />
                        </div>

                        <p class="mt-2 text-sm font-medium text-slate-700 dark:text-slate-300 truncate">
                            {{ $item->dinas_tujuan ?? 'Instansi belum dipilih' }}

                            @if($item->divisi)
                                <span class="text-slate-400 dark:text-slate-600 mx-1">•</span>
                                {{ $item->divisi }}
                            @endif
                        </p>

<p class="mt-2 text-sm font-medium text-slate-700 dark:text-slate-300 truncate">
    {{ $item->dinas_tujuan ?? 'Instansi belum dipilih' }}

    @if($item->divisi)
        <span class="text-slate-400 dark:text-slate-600 mx-1">•</span>
        {{ $item->divisi }}
    @endif
</p>

@if ($item->tipe === 'kelompok')
    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
        Kelompok · {{ $item->anggotas_count }} anggota
    </p>
@endif

                    </div>

                    {{-- PERIODE DAN TANGGAL --}}
                    <div class="sm:text-right space-y-1">
                        @if($item->tanggal_mulai && $item->tanggal_selesai)
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}
                                &ndash;
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d M Y') }}
                            </p>
                        @endif

                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Diajukan {{ $item->created_at->translatedFormat('d M Y, H:i') }}
                        </p>
                    </div>

                   {{-- AKSI --}}
<div class="sm:w-32 sm:flex sm:justify-end">
    <x-button
        :href="route('user.pendaftaran.show', $item->id)"
        variant="soft"
        size="sm"
    >
        Lihat Detail
    </x-button>
</div>

                </div>
            </x-card>

        @empty

            {{-- EMPTY STATE --}}
            <x-card
                class="!bg-white dark:!bg-[#111b32]
                       !border-dashed !border-slate-300 dark:!border-slate-700
                       text-center !py-12"
            >
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl
                            bg-blue-50 dark:bg-blue-500/10
                            border border-blue-100 dark:border-blue-500/20
                            flex items-center justify-center">
                    <span class="text-3xl">📭</span>
                </div>

                <h3 class="text-base font-bold text-slate-900 dark:text-white">
                    Belum Ada Riwayat Pendaftaran
                </h3>

                <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 mb-5">
                    Pengajuan yang pernah kamu kirim akan muncul di halaman ini.
                </p>

                <x-button :href="route('user.pendaftaran.create')">
                    Daftar Magang
                </x-button>
            </x-card>

        @endforelse

    </div>

</x-page>
@endsection
