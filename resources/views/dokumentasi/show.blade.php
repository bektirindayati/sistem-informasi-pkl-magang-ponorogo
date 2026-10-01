@extends('user.layout.app')

@section('title', $dokumentasi->judul_kegiatan)

@section('content')
@php
    $jumlah  = $dokumentasi->fotos->count();
    $tanggal = \Carbon\Carbon::parse($dokumentasi->tanggal_pelaksanaan ?? $dokumentasi->created_at)->timezone('Asia/Jakarta');
    $jam     = $dokumentasi->created_at?->copy()->timezone('Asia/Jakarta');

    // Data lama (sebelum ada uraian per foto) masih punya satu deskripsi umum.
    $deskripsiLama = filled($dokumentasi->deskripsi ?? null)
        && $dokumentasi->fotos->every(fn ($foto) => blank($foto->uraian));
@endphp

<x-page>

    {{-- BREADCRUMB --}}
    <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('home') }}#dokumentasi" class="hover:text-blue-600 transition">Dokumentasi</a>

        @if($dokumentasi->kategori_badge)
            <span class="text-slate-300">/</span>
            <span class="text-xs font-semibold uppercase tracking-wide text-blue-600">{{ $dokumentasi->kategori_badge }}</span>
        @endif
    </nav>

    <article class="bg-white border border-slate-200 rounded-lg overflow-hidden">

        {{-- HEADER (rata tengah) --}}
        <header class="px-5 sm:px-10 pt-8 sm:pt-10 pb-7 text-center border-b border-slate-200">
            <h1 class="max-w-4xl mx-auto text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 leading-snug break-words">
                {{ $dokumentasi->judul_kegiatan }}
            </h1>

            <div class="mt-4 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm text-slate-600">

                @if($dokumentasi->tempat_pelaksanaan)
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
                        </svg>
                        {{ $dokumentasi->tempat_pelaksanaan }}
                    </span>
                @endif

                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $tanggal->translatedFormat('j F Y') }}
                </span>

                @if($jam)
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $jam->format('H:i') }} WIB
                    </span>
                @endif
            </div>
        </header>

        {{-- ISI: FOTO + URAIAN --}}
        <div class="px-5 sm:px-10 py-8 sm:py-10">

            @if($deskripsiLama)
                <p class="mb-10 text-[15px] sm:text-base text-slate-700 leading-8 text-justify whitespace-pre-line break-words">{{ $dokumentasi->deskripsi }}</p>
            @endif

            @if($jumlah > 0)
                <div class="space-y-10">
                    @foreach($dokumentasi->fotos as $i => $foto)
                        <section class="space-y-5">
                            <figure class="bg-slate-100 rounded-md overflow-hidden">
                                <img src="{{ $foto->url }}"
                                     alt="{{ $dokumentasi->judul_kegiatan }} - foto {{ $i + 1 }}"
                                     loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                                     class="block w-full h-auto max-h-[640px] object-contain">
                            </figure>

                            @if($foto->uraian)
                                <p class="text-[15px] sm:text-base text-slate-700 leading-8 text-justify whitespace-pre-line break-words">{{ $foto->uraian }}</p>
                            @endif
                        </section>
                    @endforeach
                </div>
            @else
                <div class="py-14 text-center">
                    <div class="text-4xl mb-3">📷</div>
                    <p class="text-sm text-slate-400">Belum ada foto untuk kegiatan ini.</p>
                </div>
            @endif
        </div>

        {{-- FOOTER ARTIKEL --}}
        <footer class="px-5 sm:px-10 py-5 border-t border-slate-200 bg-slate-50 flex justify-center">
            <x-button :href="route('home') . '#dokumentasi'" variant="secondary" size="sm">
                Kembali ke Galeri
            </x-button>
        </footer>
    </article>

    {{-- KEGIATAN LAINNYA --}}
    @if($lainnya->isNotEmpty())
        <section class="space-y-4 pt-4">
            <h2 class="text-lg font-semibold text-slate-900">Dokumentasi lainnya</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                @foreach($lainnya as $item)
                    <a href="{{ route('dokumentasi.show', $item) }}"
                       class="block h-full rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                        <x-galeri-card
                            :tag="$item->kategori_badge"
                            :judul="$item->judul_kegiatan"
                            :gambar="$item->sampul_url"
                            :tempat="$item->tempat_pelaksanaan"
                            :tanggal="$item->tanggal_pelaksanaan ?? $item->created_at"
                            :waktu="$item->created_at"
                        />
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</x-page>
@endsection