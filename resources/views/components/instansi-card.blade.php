{{-- Komponen: <x-instansi-card :slug="..." :nama="..." :deskripsi="..." :gambar="..." :rekap="..." /> --}}
@props(['slug', 'nama', 'deskripsi' => null, 'icon' => null, 'gambar' => null, 'rekap' => null])

<a href="{{ route('user.pendaftaran.create', ['dinas' => $slug]) }}"
   class="group flex h-full items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm
          transition duration-200 hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md
          focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500
          dark:border-slate-700 dark:bg-slate-900 dark:hover:border-cyan-500/60 dark:hover:shadow-none">

    @if($gambar)
        <div class="h-10 w-10 shrink-0 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700">
            <img src="{{ asset($gambar) }}" alt="Logo {{ $nama }}" class="h-full w-full object-cover">
        </div>
    @else
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700 transition duration-200
                    group-hover:bg-blue-600 group-hover:text-white
                    dark:bg-blue-500/15 dark:text-cyan-300 dark:group-hover:bg-cyan-600 dark:group-hover:text-white">
            <x-ikon name="building" class="h-5 w-5" />
        </div>
    @endif

    <div class="flex min-w-0 flex-1 flex-col self-stretch">
        <h3 class="line-clamp-2 text-sm font-bold leading-snug text-slate-900 transition-colors duration-200
                   group-hover:text-blue-700 dark:text-white dark:group-hover:text-cyan-300">
            {{ $nama }}
        </h3>

        <p class="mt-1 line-clamp-2 text-[13px] leading-relaxed text-slate-600 dark:text-slate-300">
            {{ filled($deskripsi) ? $deskripsi : 'Instansi penempatan magang Kabupaten Ponorogo.' }}
        </p>

        {{-- Rekap pendaftar / aktif / selesai --}}
        @if($rekap)
            <div class="mt-2.5 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs font-medium text-slate-600 dark:text-slate-300">
                <span class="inline-flex items-center gap-1.5">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-blue-600"></span>{{ $rekap['pendaftar'] ?? 0 }} pendaftar
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>{{ $rekap['aktif'] ?? 0 }} aktif
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-slate-400"></span>{{ $rekap['selesai'] ?? 0 }} selesai
                </span>
            </div>
        @endif

        <span class="mt-auto inline-flex items-center gap-1 pt-2.5 text-[13px] font-bold text-blue-700 dark:text-cyan-400">
            Daftar Sekarang
            <svg class="h-3.5 w-3.5 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </span>
    </div>
</a>