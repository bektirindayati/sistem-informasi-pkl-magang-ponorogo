{{--Komponen: <x-section-heading eyebrow="Instansi Tujuan" title="Daftar Dinas & Instansi">
                  Pilih instansi penempatan magang yang sesuai...
              </x-section-heading>--}}
@props(['eyebrow', 'title'])

<div class="text-center max-w-2xl mx-auto mb-10">
    <span class="px-3 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-xs font-extrabold tracking-wider uppercase">
        {{ $eyebrow }}
    </span>
    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-3">
        {{ $title }}
    </h2>
    <p class="text-slate-600 text-xs sm:text-sm mt-2">
        {{ $slot }}
    </p>
</div>
