{{--
    Kartu dokumentasi gaya portal berita: foto + badge kategori, judul,
    lalu footer berisi nama dinas / tanggal / jam.

    <x-galeri-card
        tag="Pelatihan"
        judul="Workshop Pengembangan Keahlian Digital"
        :gambar="$item->sampul_url"
        tempat="Dinas Kominfo Kabupaten Ponorogo"
        :tanggal="$item->tanggal_pelaksanaan ?? $item->created_at"
        :waktu="$item->created_at"
    />

    Judul dipotong titik-titik setelah 3 baris. Bungkus dengan <a href="...">
    untuk membuka halaman detail.
--}}
@props(['tag' => null, 'judul', 'gambar' => null, 'tempat' => null, 'tanggal' => null, 'waktu' => null])

@php
    $tgl = $tanggal ? \Carbon\Carbon::parse($tanggal)->timezone('Asia/Jakarta') : null;
    $jam = $waktu ? \Carbon\Carbon::parse($waktu)->timezone('Asia/Jakarta') : null;
@endphp

<article class="group h-full flex flex-col bg-white border border-slate-200 rounded-lg overflow-hidden
                transition hover:border-blue-300 hover:shadow-md">

    {{-- FOTO + BADGE KATEGORI --}}
    <div class="relative aspect-[16/10] bg-slate-100 overflow-hidden">
        @if($gambar)
            <img src="{{ $gambar }}" alt="{{ $judul }}" loading="lazy"
                 class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105">
        @else
            <div class="absolute inset-0 flex items-center justify-center text-xs text-slate-400">Tanpa foto</div>
        @endif

        @if($tag)
            <span title="{{ $tag }}"
                  class="absolute top-3 left-3 max-w-[80%] truncate rounded bg-blue-600 px-2.5 py-1
                         text-[11px] font-semibold uppercase tracking-wide text-white">
                {{ $tag }}
            </span>
        @endif
    </div>

    {{-- JUDUL --}}
    <div class="flex-1 p-5">
        <h3 title="{{ $judul }}"
            class="text-base font-semibold leading-snug text-slate-900 line-clamp-3 break-words transition-colors group-hover:text-blue-700">
            {{ $judul }}
        </h3>
    </div>

    {{-- FOOTER: DINAS • TANGGAL • JAM --}}
    @if($tempat || $tgl || $jam)
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 border-t border-slate-200 bg-slate-50 px-5 py-3 text-xs text-slate-500">

            @if($tempat)
                <span class="inline-flex min-w-0 items-center gap-1.5">
                    <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/>
                    </svg>
                    <span class="truncate max-w-[11rem]" title="{{ $tempat }}">{{ $tempat }}</span>
                </span>
            @endif

            @if($tgl)
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    {{ $tgl->translatedFormat('j F Y') }}
                </span>
            @endif

            @if($jam)
                <span class="inline-flex items-center gap-1.5">
                    <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $jam->format('H:i') }} WIB
                </span>
            @endif
        </div>
    @endif
</article>