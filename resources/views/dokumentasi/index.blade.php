@extends('user.layout.app')

@section('title', 'Dokumentasi Aktivitas Magang')

@section('content')
<x-page>

    {{-- BREADCRUMB --}}
    <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-sm text-slate-500 dark:text-slate-300">
        <a href="{{ route('home') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Beranda</a>
        <span class="text-slate-300 dark:text-slate-600">/</span>
        <span class="font-medium text-slate-700 dark:text-slate-100">Dokumentasi</span>
    </nav>

    <section>
        <x-section-heading
            eyebrow="Galeri & Kegiatan"
            title="Dokumentasi Aktivitas Magang"
        >
            Seluruh dokumentasi kegiatan magang, diurutkan dari yang terbaru. Total {{ $dokumentasis->total() }} kegiatan.
        </x-section-heading>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
            @forelse($dokumentasis as $item)
                <a href="{{ route('dokumentasi.show', $item) }}"
                   class="block h-full rounded-2xl focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500
                          focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-950">
                    <x-galeri-card
                        :tag="$item->kategori_badge"
                        :judul="$item->judul_kegiatan"
                        :gambar="$item->sampul_url"
                        :tempat="$item->tempat_pelaksanaan"
                        :tanggal="$item->tanggal_pelaksanaan"
                        :ringkasan="$item->ringkasan"
                    />
                </a>
            @empty
                <div class="col-span-full py-10 text-center">
                    <x-ikon name="photo" class="mx-auto mb-3 h-10 w-10 text-slate-400" />
                    <h3 class="font-semibold text-slate-700 dark:text-slate-100">Belum ada dokumentasi kegiatan</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-300">
                        Dokumentasi kegiatan magang akan tampil di sini setelah diunggah admin.
                    </p>
                </div>
            @endforelse
        </div>

        @if ($dokumentasis->hasPages())
            <div class="mt-8">{{ $dokumentasis->onEachSide(1)->links() }}</div>
        @endif
    </section>

</x-page>
@endsection