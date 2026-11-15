@extends('user.layout.app')

@section('title', 'Daftar Dinas & Instansi')

@section('content')
<x-page>

    {{-- BREADCRUMB --}}
    <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-sm text-slate-500 dark:text-slate-300">
        <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Beranda</a>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <span class="font-medium text-slate-700 dark:text-slate-100">Instansi Tujuan</span>
    </nav>

    <section>
        <x-section-heading
            eyebrow="Instansi Tujuan"
            title="Daftar Dinas & Instansi Kabupaten Ponorogo"
        >
            Pilih instansi penempatan magang yang tersedia. Total {{ $dinases->total() }} instansi.
        </x-section-heading>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 md:grid-cols-3">
            @forelse ($dinases as $dinas)
                <x-instansi-card
                    :slug="Str::slug($dinas->nama_dinas)"
                    :nama="$dinas->nama_dinas"
                    :deskripsi="$dinas->deskripsi"
                    :gambar="$dinas->gambar ?? null"
                    :rekap="$dinas->rekap ?? null"
                />
            @empty
                <div class="col-span-full py-10 text-center">
                    <x-ikon name="building" class="mx-auto mb-3 h-10 w-10 text-slate-400" />
                    <h3 class="font-semibold text-slate-700 dark:text-slate-200">Belum ada instansi yang tersedia</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Silakan cek kembali nanti.</p>
                </div>
            @endforelse
        </div>

        @if ($dinases->hasPages())
            <div class="mt-8">{{ $dinases->onEachSide(1)->links() }}</div>
        @endif
    </section>

</x-page>
@endsection