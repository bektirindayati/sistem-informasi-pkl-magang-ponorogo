{{--Komponen: <x-galeri-card tag="Judul Kegiatan" judul="Kategori" :gambar="asset('storage/'.$item->foto)">
                  Deskripsi kegiatan...
              </x-galeri-card>--}}
@props(['tag', 'judul', 'gradient' => 'from-blue-600/20 to-cyan-600/20', 'gambar' => null])

<div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm group">
    <div class="h-48 bg-slate-200 flex items-center justify-center text-slate-400 font-bold overflow-hidden relative">
        @if($gambar)
            <img src="{{ $gambar }}" alt="{{ $judul }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/10 to-transparent"></div>
            <span class="relative z-10 text-xs text-white font-bold bg-black/40 px-3.5 py-1.5 rounded-lg shadow-xs backdrop-blur-xs">{{ $tag }}</span>
        @else
            <div class="absolute inset-0 bg-gradient-to-tr {{ $gradient }} group-hover:scale-105 transition duration-500"></div>
            <span class="relative z-10 text-xs text-slate-700 font-bold bg-white/90 px-3.5 py-1.5 rounded-lg shadow-xs backdrop-blur-xs">{{ $tag }}</span>
        @endif
    </div>
    <div class="p-5">
        <h4 class="font-bold text-slate-900 text-base">{{ $judul }}</h4>
        <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $slot }}</p>
    </div>
</div>