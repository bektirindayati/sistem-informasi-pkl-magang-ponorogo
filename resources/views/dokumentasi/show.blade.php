@extends('user.layout.app')

@section('title', $dokumentasi->judul_kegiatan)

@section('content')
@php
    $fotos   = $dokumentasi->fotos;
    $jumlah  = $fotos->count();
    $tanggal = $dokumentasi->tanggal_pelaksanaan;
@endphp

<x-page>

    {{-- BREADCRUMB --}}
    <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-sm text-slate-500 dark:text-slate-300">
        <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Beranda</a>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <a href="{{ route('home') }}#dokumentasi" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Dokumentasi</a>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <span class="max-w-[14rem] sm:max-w-xs truncate font-medium text-slate-700 dark:text-slate-100">
            {{ $dokumentasi->judul_kegiatan }}
        </span>
    </nav>

    <article class="w-full overflow-hidden rounded-2xl border border-slate-200 bg-white
                dark:border-slate-800 dark:bg-slate-900">

        {{-- HEADER --}}
        <header class="border-b border-slate-200 px-5 pb-6 pt-7 dark:border-slate-800 sm:px-10 sm:pt-9">

            @if($dokumentasi->kategori_badge)
                <span class="inline-block max-w-full truncate rounded-full bg-blue-50 px-3 py-1 text-[11px] font-bold
                             uppercase tracking-wide text-blue-700 dark:bg-blue-500/15 dark:text-blue-300">
                    {{ $dokumentasi->kategori_badge }}
                </span>
            @endif

            <h1 class="mt-3 break-words text-2xl font-extrabold leading-snug tracking-tight text-slate-900
                       dark:text-white sm:text-3xl lg:text-4xl">
                {{ $dokumentasi->judul_kegiatan }}
            </h1>

            <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm text-slate-600 dark:text-slate-200">
                <span class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-slate-400 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $tanggal->translatedFormat('l, j F Y') }}
                </span>

                @if($dokumentasi->tempat_pelaksanaan)
                    <span class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
                        </svg>
                        {{ $dokumentasi->tempat_pelaksanaan }}
                    </span>
                @endif

                @if($jumlah > 0)
                    <span class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 shrink-0 text-slate-400 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M4 16l4-4a3 3 0 014 0l4 4m-2-2l1-1a3 3 0 014 0l2 2M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/>
                        </svg>
                        {{ $jumlah }} foto
                    </span>
                @endif
            </div>
        </header>

        {{-- ISI: FOTO + URAIAN --}}
        <div class="px-5 py-8 sm:px-10 sm:py-10">
            @if($jumlah > 0)
                <div class="space-y-10">
                    @foreach($fotos as $i => $foto)
                        <section class="space-y-5">
                            <figure class="relative overflow-hidden rounded-2xl bg-slate-100 ring-1 ring-slate-200
                                           dark:bg-slate-800 dark:ring-slate-700">
                                <button type="button"
                                        data-lightbox="{{ $foto->url }}"
                                        aria-label="Perbesar foto {{ $i + 1 }}"
                                        class="block w-full cursor-zoom-in focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                    <img src="{{ $foto->url }}"
                                         alt="{{ $dokumentasi->judul_kegiatan }} - foto {{ $i + 1 }}"
                                         loading="{{ $i === 0 ? 'eager' : 'lazy' }}"
                                         class="mx-auto block max-h-[70vh] w-full object-contain">
                                </button>

                                @if($jumlah > 1)
                                    <figcaption class="pointer-events-none absolute bottom-3 right-3 rounded-full bg-slate-900/70
                                                       px-2.5 py-1 text-[11px] font-semibold text-white backdrop-blur">
                                        {{ $i + 1 }} / {{ $jumlah }}
                                    </figcaption>
                                @endif
                            </figure>

                            @if($foto->uraian)
                                <p class="w-full whitespace-pre-line break-words text-base leading-8
          text-slate-700 dark:text-slate-100">{{ $foto->uraian }}</p>
                            @endif
                        </section>
                    @endforeach
                </div>
            @else
                <div class="py-14 text-center">
                    <div class="mb-3 text-4xl">📷</div>
                    <p class="text-sm text-slate-400 dark:text-slate-300">Belum ada foto untuk kegiatan ini.</p>
                </div>
            @endif
        </div>

        {{-- FOOTER ARTIKEL --}}
        <footer class="flex justify-center border-t border-slate-200 bg-slate-50 px-5 py-5
                       dark:border-slate-800 dark:bg-slate-950/50 sm:px-10">
            <x-button :href="route('home') . '#dokumentasi'" variant="secondary" size="sm">
                Kembali ke Galeri
            </x-button>
        </footer>
    </article>

    {{-- KEGIATAN LAINNYA --}}
    @if($lainnya->isNotEmpty())
        <section class="space-y-4 pt-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Dokumentasi lainnya</h2>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
                @foreach($lainnya as $item)
                    <a href="{{ route('dokumentasi.show', $item) }}"
                       class="block h-full rounded-2xl focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500
                              focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-950">
                        <x-galeri-card
                            :tag="$item->kategori_badge"
                            :judul="$item->judul_kegiatan"
                            :gambar="$item->sampul_url"
                            :tempat="$item->tempat_pelaksanaan"
                            :tanggal="$item->tanggal_pelaksanaan"
                            :ringkasan="$item->ringkasan"
                        />
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- LIGHTBOX FOTO --}}
    <dialog id="lightbox"
            class="m-auto max-h-[92vh] max-w-[94vw] overflow-visible bg-transparent p-0
                   backdrop:bg-slate-950/85 backdrop:backdrop-blur-sm">
        <form method="dialog">
            <button class="absolute right-2 top-2 z-10 flex h-10 w-10 items-center justify-center rounded-full
                           bg-white/90 text-slate-700 shadow-lg transition hover:bg-white
                           dark:bg-slate-900/90 dark:text-white dark:hover:bg-slate-900"
                    aria-label="Tutup">✕</button>
        </form>
        <img id="lightboxImg" src="" alt="" class="block max-h-[88vh] max-w-[94vw] rounded-xl object-contain">
    </dialog>

</x-page>

<script>
(() => {
    const dialog = document.getElementById('lightbox');
    const gambar = document.getElementById('lightboxImg');
    if (!dialog || !gambar) return;

    document.querySelectorAll('[data-lightbox]').forEach(tombol => {
        tombol.addEventListener('click', () => {
            gambar.src = tombol.dataset.lightbox;
            dialog.showModal();
            document.documentElement.style.overflow = 'hidden';
        });
    });

    // Klik area gelap di luar foto = tutup (Esc sudah ditangani browser)
    dialog.addEventListener('click', e => { if (e.target === dialog) dialog.close(); });

    dialog.addEventListener('close', () => {
        gambar.removeAttribute('src');
        document.documentElement.style.overflow = '';
    });
})();
</script>
@endsection 