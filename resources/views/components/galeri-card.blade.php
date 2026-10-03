{{-- 
    Kartu dokumentasi gaya portal berita modern:
    foto + badge kategori, judul,
    lalu footer berisi tempat / tanggal / jam.
--}}

@props([
    'tag' => null,
    'judul',
    'gambar' => null,
    'tempat' => null,
    'tanggal' => null,
    'waktu' => null
])

@php
    $tgl = $tanggal
        ? \Carbon\Carbon::parse($tanggal)->timezone('Asia/Jakarta')
        : null;

    $jam = $waktu
        ? \Carbon\Carbon::parse($waktu)->timezone('Asia/Jakarta')
        : null;
@endphp


<article
    class="group h-full flex flex-col
           bg-white
           border-2 border-blue-100
           overflow-hidden
           shadow-sm
           transition-all duration-300
           hover:-translate-y-1
           hover:border-blue-400
           hover:shadow-lg hover:shadow-blue-100/70"
>

    {{-- =========================================================
        FOTO + BADGE
    ========================================================== --}}
    <div class="relative aspect-[16/10] bg-slate-100 overflow-hidden">

        @if($gambar)

            <img
                src="{{ $gambar }}"
                alt="{{ $judul }}"
                loading="lazy"
                class="absolute inset-0
                       w-full h-full
                       object-cover
                       transition-transform duration-500
                       group-hover:scale-[1.04]"
            >

        @else

            <div
                class="absolute inset-0
                       flex items-center justify-center
                       bg-slate-100
                       text-xs font-semibold
                       text-slate-400"
            >
                Tanpa foto
            </div>

        @endif


        {{-- BADGE KATEGORI --}}
        @if($tag)

            <span
                title="{{ $tag }}"
                class="absolute top-3 left-3
                       max-w-[80%]
                       truncate
                       border border-blue-500
                       bg-blue-700
                       px-3 py-1.5
                       text-[10px] sm:text-[11px]
                       font-extrabold
                       uppercase
                       tracking-[0.08em]
                       text-white
                       shadow-md"
            >
                {{ $tag }}
            </span>

        @endif

    </div>


    {{-- =========================================================
        JUDUL
    ========================================================== --}}
    <div class="flex-1 px-5 py-5 sm:px-6 sm:py-6">

        <h3
            title="{{ $judul }}"
            class="text-[16px] sm:text-[17px]
                   font-extrabold
                   leading-snug
                   tracking-[-0.01em]
                   text-slate-900
                   line-clamp-3
                   break-words
                   transition-colors duration-200
                   group-hover:text-blue-700"
        >
            {{ $judul }}
        </h3>

    </div>


    {{-- =========================================================
        FOOTER INFORMASI
        TEMPAT • TANGGAL • JAM
    ========================================================== --}}
    @if($tempat || $tgl || $jam)

        <div
            class="border-t-2 border-blue-700
                   bg-blue-700
                   px-5 py-4 sm:px-6
                   text-[11px] sm:text-xs
                   font-semibold
                   text-white"
        >

            <div class="flex flex-wrap items-center gap-x-4 gap-y-2.5">

                {{-- TEMPAT --}}
                @if($tempat)

                    <span
                        class="inline-flex min-w-0
                               items-center gap-2"
                    >

                        <svg
                            class="h-4 w-4 shrink-0 text-blue-100"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"
                            />
                        </svg>

                        <span
                            class="truncate max-w-[12rem] sm:max-w-[14rem]"
                            title="{{ $tempat }}"
                        >
                            {{ $tempat }}
                        </span>

                    </span>

                @endif


                {{-- TANGGAL --}}
                @if($tgl)

                    <span
                        class="inline-flex items-center gap-2"
                    >

                        <svg
                            class="h-4 w-4 shrink-0 text-blue-100"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>

                        <span class="whitespace-nowrap">
                            {{ $tgl->translatedFormat('j F Y') }}
                        </span>

                    </span>

                @endif


                {{-- JAM --}}
                @if($jam)

                    <span
                        class="inline-flex items-center gap-2"
                    >

                        <svg
                            class="h-4 w-4 shrink-0 text-blue-100"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>

                        <span class="whitespace-nowrap">
                            {{ $jam->format('H:i') }} WIB
                        </span>

                    </span>

                @endif

            </div>

        </div>

    @endif

</article>