
{{-- Komponen: <x-langkah-card nomor="1" judul="Login Akun">Masuk sistem menggunakan akun...</x-langkah-card> --}}

@props(['nomor', 'judul'])

<div class="group p-4
            bg-blue-50/70
            border border-blue-200
            rounded-2xl shadow-sm
            hover:border-blue-400 hover:bg-blue-50
            hover:shadow-md
            dark:bg-slate-800
            dark:border-slate-700
            dark:hover:bg-slate-800/80
            dark:hover:border-cyan-500/40
            dark:shadow-none
            transition-all duration-300">

    {{-- NOMOR LANGKAH --}}
    <div class="text-blue-700 dark:text-cyan-400
                font-extrabold text-sm mb-1">
        Langkah {{ $nomor }}
    </div>

    {{-- JUDUL --}}
    <h3 class="font-bold text-xs
               text-slate-900 dark:text-white
               mb-1">
        {{ $judul }}
    </h3>

    {{-- DESKRIPSI --}}
    <p class="text-[11px]
              text-slate-600 dark:text-slate-300
              leading-relaxed">
        {{ $slot }}
    </p>

</div>
