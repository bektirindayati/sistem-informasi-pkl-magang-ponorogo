{{-- Komponen: <x-langkah-card nomor="1" judul="Login Akun">Masuk sistem menggunakan akun...</x-langkah-card> --}}
@props(['nomor', 'judul'])

<div class="p-4 bg-blue-50/40 border border-blue-400 rounded-2xl shadow-sm transition-all duration-300">
    <div class="text-blue-600 font-extrabold text-sm mb-1">Langkah {{ $nomor }}</div>
    <h3 class="font-bold text-xs text-slate-900 mb-1">{{ $judul }}</h3>
    <p class="text-[11px] text-slate-600 leading-relaxed">{{ $slot }}</p>
</div>
