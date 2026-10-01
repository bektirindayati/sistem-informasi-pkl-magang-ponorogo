{{-- resources/views/user/pendaftaran/riwayat.blade.php --}}

@extends('user.layout.app')

@section('title', 'Riwayat Pendaftaran')

@section('content')
<x-page>

    <x-page-header
        eyebrow="Pendaftaran"
        title="Riwayat Pendaftaran"
        :back="route('user.dashboard')"
    >
        Semua pengajuan magang/PKL yang pernah kamu kirim, termasuk pengajuan sebelumnya.
    </x-page-header>

    <div class="space-y-3">

        @forelse($riwayat as $index => $item)

            <x-card :pad="false" class="px-5 py-4 sm:px-6 hover:border-slate-300 transition">
                <div class="grid grid-cols-1 sm:grid-cols-[1fr_auto_auto] sm:items-center gap-x-8 gap-y-3">

                    {{-- Informasi pengajuan --}}
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-semibold text-slate-900">
                                Pendaftaran #{{ $riwayat->count() - $index }}
                            </span>
                            <x-status-badge :status="$item->status" />
                        </div>

                        <p class="mt-1.5 text-sm text-slate-600 truncate">
                            {{ $item->dinas_tujuan ?? 'Instansi belum dipilih' }}
                            @if($item->divisi)
                                <span class="text-slate-300 mx-1">•</span>{{ $item->divisi }}
                            @endif
                        </p>
                    </div>

                    {{-- Periode & tanggal diajukan --}}
                    <div class="sm:text-right text-xs text-slate-400 space-y-0.5">
                        @if($item->tanggal_mulai && $item->tanggal_selesai)
                            <p class="font-medium text-slate-500">
                                {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}
                                &ndash;
                                {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d M Y') }}
                            </p>
                        @endif
                        <p>Diajukan {{ $item->created_at->translatedFormat('d M Y, H:i') }}</p>
                    </div>

                    {{-- Aksi (hanya pengajuan terbaru) --}}
                    <div class="sm:w-32 sm:flex sm:justify-end">
                        @if($item->id === optional($riwayat->first())->id)
                            <x-button :href="route('user.pendaftaran.status')" variant="soft" size="sm">
                                Lihat Status
                            </x-button>
                        @endif
                    </div>
                </div>
            </x-card>

        @empty

            <x-card class="border-dashed border-slate-300 text-center !py-12">
                <div class="text-3xl mb-3">📭</div>
                <h3 class="text-base font-semibold text-slate-900">Belum Ada Riwayat Pendaftaran</h3>
                <p class="text-sm text-slate-500 mt-1 mb-5">Pengajuan yang pernah kamu kirim akan muncul di halaman ini.</p>
                <x-button :href="route('user.pendaftaran.create')">Daftar Magang</x-button>
            </x-card>

        @endforelse

    </div>

</x-page>
@endsection