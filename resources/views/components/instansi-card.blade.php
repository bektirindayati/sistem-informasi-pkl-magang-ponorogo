
{{-- Komponen: <x-instansi-card :slug="$item['slug']" :nama="$item['nama']" :deskripsi="$item['deskripsi']" :icon="$item['icon']" :gambar="$item['gambar'] ?? null" :rekap="$item['rekap'] ?? null" /> --}}
@props(['slug', 'nama', 'deskripsi', 'icon' => '🏢', 'gambar' => null, 'rekap' => null])

<a href="{{ route('user.pendaftaran.create', ['dinas' => $slug]) }}"
   class="group flex items-start gap-3.5
          bg-white dark:bg-slate-900
          p-4 sm:p-5 rounded-2xl
          border border-blue-100 dark:border-slate-700
          shadow-sm hover:shadow-md
          hover:border-blue-400 dark:hover:border-cyan-600
          transition duration-200">

    @if($gambar)
        <div class="w-10 h-10 sm:w-11 sm:h-11 shrink-0 overflow-hidden rounded-xl
                    border border-blue-100 dark:border-slate-700">
            <img src="{{ asset($gambar) }}" alt="Logo {{ $nama }}"
                 class="w-full h-full object-cover">
        </div>
    @else
        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl
                    bg-blue-50 text-blue-700
                    dark:bg-blue-950 dark:text-cyan-300
                    flex items-center justify-center font-bold text-lg shrink-0
                    group-hover:bg-blue-600 group-hover:text-white
                    dark:group-hover:bg-cyan-600 dark:group-hover:text-white
                    transition duration-200">
            {{ $icon }}
        </div>
    @endif

    <div class="flex flex-col min-w-0 flex-1">
        <h3 class="text-sm sm:text-base font-bold
                   text-slate-900 dark:text-white
                   mb-0.5 group-hover:text-blue-700
                   dark:group-hover:text-cyan-300
                   transition duration-200 truncate">
            {{ $nama }}
        </h3>

        <p class="text-[11px] sm:text-xs
                  text-slate-600 dark:text-slate-400
                  mb-2.5 line-clamp-2">
            {{ $deskripsi }}
        </p>

        {{-- Badge rekap pendaftar/aktif/selesai --}}
        @if($rekap)
            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-2.5">
                <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] font-semibold
                             text-slate-600 dark:text-slate-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2F5BFF] shrink-0"></span>
                    {{ $rekap['pendaftar'] ?? 0 }} pendaftar
                </span>

                <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] font-semibold
                             text-slate-600 dark:text-slate-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                    {{ $rekap['aktif'] ?? 0 }} aktif
                </span>

                <span class="inline-flex items-center gap-1 text-[10px] sm:text-[11px] font-semibold
                             text-slate-600 dark:text-slate-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400 shrink-0"></span>
                    {{ $rekap['selesai'] ?? 0 }} selesai
                </span>
            </div>
        @endif

        <span class="text-[11px] sm:text-xs font-semibold
                     text-blue-700 dark:text-cyan-400
                     flex items-center mt-auto
                     group-hover:gap-1.5 transition-all duration-200">
            Daftar Sekarang
        </span>
    </div>
</a>
