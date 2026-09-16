{{--Komponen: <x-instansi-card :slug="$item['slug']" :nama="$item['nama']" :deskripsi="$item['deskripsi']" :icon="$item['icon']" :gambar="$item['gambar'] ?? null" />
 <a>...</a> yang sebelumnya--}}
@props(['slug', 'nama', 'deskripsi', 'icon' => '🏢', 'gambar' => null])

<a href="{{ route('user.pendaftaran.create', ['dinas' => $slug]) }}"
   class="group flex items-start gap-3.5 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-500 transition duration-200">

    @if($gambar)
        <div class="w-10 h-10 sm:w-11 sm:h-11 shrink-0 overflow-hidden rounded-xl border border-slate-100">
            <img src="{{ asset($gambar) }}" alt="Logo {{ $nama }}" class="w-full h-full object-cover">
        </div>
    @else
        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg shrink-0 group-hover:bg-blue-600 group-hover:text-white transition duration-200">
            {{ $icon }}
        </div>
    @endif

    <div class="flex flex-col min-w-0">
        <h3 class="text-sm sm:text-base font-bold text-slate-800 mb-0.5 group-hover:text-blue-600 transition duration-200 truncate">
            {{ $nama }}
        </h3>
        <p class="text-[11px] sm:text-xs text-slate-500 mb-2.5 line-clamp-2">{{ $deskripsi }}</p>
        <span class="text-[11px] sm:text-xs font-semibold text-blue-600 flex items-center mt-auto">Daftar Sekarang</span>
    </div>
</a>
