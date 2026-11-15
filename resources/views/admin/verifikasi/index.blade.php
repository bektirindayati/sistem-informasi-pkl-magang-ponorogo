@extends('admin.layout.app')

@section('page-title', 'Verifikasi Berkas Masuk')

@section('content')

    @php
        $tgl = fn ($d) => $d ? \Carbon\Carbon::parse($d)->translatedFormat('d M Y') : '-';
        $jumlah = (isset($pendaftars) && method_exists($pendaftars, 'total')) ? $pendaftars->total() : count($pendaftars ?? []);
    @endphp

    @if (session('success'))
        <x-alert type="success"> {{ session('success') }}</x-alert>
    @endif
    @if (session('error'))
        <x-alert type="error"> {{ session('error') }}</x-alert>
    @endif
    @if ($errors->any())
        <x-alert type="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </x-alert>
    @endif

    <section class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800
                    shadow-sm dark:shadow-none overflow-hidden">

        {{-- HEADER: judul + pencarian otomatis --}}
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800
                    flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">Daftar Pengajuan Magang</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-200
                                 text-xs font-bold whitespace-nowrap">{{ $jumlah }} pengajuan</span>
                </div>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Ubah status langsung melalui pilihan status pada tabel.
                </p>
            </div>

            <form method="GET" id="formCari" class="flex items-center gap-2 lg:w-[460px]">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 10.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z"/>
                    </svg>
                    <input type="search" name="search" id="inputCari" value="{{ request('search') }}" autocomplete="off"
                           placeholder="Ketik nama atau NIM, termasuk anggota kelompok..."
                           class="w-full h-10 pl-9 pr-3 rounded-xl border border-slate-200 dark:border-slate-700
                                  bg-white dark:bg-slate-950/60 text-sm text-slate-700 dark:text-slate-100
                                  placeholder:text-slate-400 dark:placeholder:text-slate-500
                                  focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 dark:focus:ring-cyan-500/20">
                </div>

                @if (request('search'))
                    <a href="{{ route('admin.verifikasi.index') }}"
                       class="h-10 inline-flex items-center px-2 text-sm font-bold transition
                              text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- TABEL --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[760px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800
                               text-slate-600 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                        <th class="py-3 px-6">Nama Pendaftar</th>
                        <th class="py-3 px-6">Instansi Tujuan &amp; Periode</th>
                        <th class="py-3 px-6 w-52">Status Verifikasi</th>
                        <th class="py-3 px-6 text-right">Detail</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-300">
                    @forelse ($pendaftars ?? [] as $item)
                        <tr class="align-top hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">

                            <td class="py-4 px-6">
                                <a href="{{ route('admin.pendaftar.show', $item->id) }}"
                                   class="font-semibold text-slate-900 dark:text-slate-100 hover:text-cyan-600 dark:hover:text-cyan-400 transition">
                                    {{ $item->nama_lengkap }}
                                </a>

                                @if ($item->tipe === 'kelompok')
                                    <div class="mt-1"><x-admin.badge-kelompok :pendaftaran="$item" /></div>
                                @endif

                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                    {{ $item->kategori ?? '-' }} • {{ $item->instansi ?? '-' }}
                                </p>
                            </td>

                            <td class="py-4 px-6">
                                <p class="text-slate-700 dark:text-slate-200">{{ $item->dinas_tujuan ?? '-' }}</p>
                                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ $item->divisi ?? '-' }}</p>
                                <p class="mt-1 text-xs text-slate-400 dark:text-slate-500 whitespace-nowrap">
                                    {{ $tgl($item->tanggal_mulai) }} &ndash; {{ $tgl($item->tanggal_selesai) }}
                                </p>
                            </td>

                            <td class="py-4 px-6">
                                <x-admin.status-select
                                    :status="$item->status"
                                    :action="route('admin.verifikasi.update', $item->id)"
                                    :alasan-penolakan="$item->alasan_penolakan"
                                    :alasan-revisi="$item->alasan_revisi"
                                    :ada-terbit="$item->suratTerbit() || $item->suratSelesaiTerbit() || $item->sertifikatTerbit()"
                                />
                            </td>

                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.pendaftar.show', $item->id) }}"
                                   class="inline-flex h-8 items-center px-3 rounded-lg text-xs font-bold whitespace-nowrap transition
                                          bg-slate-100 hover:bg-slate-200 text-slate-700
                                          dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200">
                                    Detail Verifikasi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-14 px-6 text-center text-slate-400 dark:text-slate-500 font-semibold">
                                {{ request('search')
                                    ? 'Tidak ada hasil untuk pencarian ini.'
                                    : 'Tidak ada berkas yang perlu diverifikasi saat ini.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (isset($pendaftars) && method_exists($pendaftars, 'links') && $pendaftars->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                {{ $pendaftars->links() }}
            </div>
        @endif
    </section>

@endsection

@push('scripts')
<script>
    // Pencarian otomatis dengan debounce (logika tidak berubah)
    (() => {
        const form = document.getElementById('formCari');
        const input = document.getElementById('inputCari');
        let timer;

        if (!form || !input) return;

        if (sessionStorage.getItem('fokusCari')) {
            sessionStorage.removeItem('fokusCari');
            input.focus();
            input.setSelectionRange(input.value.length, input.value.length);
        }

        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                sessionStorage.setItem('fokusCari', '1');
                form.submit();
            }, 400);
        });
    })();
</script>
@endpush