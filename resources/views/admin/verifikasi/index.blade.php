
@extends('admin.layout.app')

@section('page-title', 'Verifikasi Berkas Masuk')

@section('content')

    @php
        $tgl = fn ($d) => $d
            ? \Carbon\Carbon::parse($d)->translatedFormat('d M Y')
            : '-';
    @endphp

    {{-- PENCARIAN OTOMATIS --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm dark:shadow-none p-4">
        <form method="GET" id="formCari" class="flex gap-3">
            <input
                type="search"
                name="search"
                id="inputCari"
                value="{{ request('search') }}"
                autocomplete="off"
                placeholder="Ketik nama atau NIM, termasuk anggota kelompok..."
                class="flex-1 min-w-0 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700
                       bg-white dark:bg-slate-950/60 text-base text-slate-700 dark:text-slate-100
                       placeholder:text-slate-400 dark:placeholder:text-slate-500
                       focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 dark:focus:ring-cyan-500/20"
            >

            @if (request('search'))
                <a
                    href="{{ route('admin.verifikasi.index') }}"
                    class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600
                           dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300
                           text-base font-semibold rounded-xl transition"
                >
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- PENCARIAN OTOMATIS DENGAN DEBOUNCE --}}
    <script>
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

    {{-- DAFTAR PENGAJUAN --}}
    <x-admin.table-card
        title="Daftar Pengajuan Magang"
        description="Ubah status langsung melalui pilihan status pada tabel."
    >
        <table class="w-full text-left border-collapse min-w-[680px]">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800
                           text-slate-700 dark:text-slate-200 font-bold text-sm uppercase tracking-wide">
                    <th class="p-4 px-6">Nama Pendaftar</th>
                    <th class="p-4 px-6">Instansi Tujuan &amp; Periode</th>
                    <th class="p-4 px-6">Status Verifikasi</th>
                    <th class="p-4 px-6 text-center">Detail</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-base text-slate-600 dark:text-slate-300">
                @forelse ($pendaftars ?? [] as $item)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">

                        {{-- NAMA PENDAFTAR --}}
                        <td class="p-4 px-6 align-top">
                            <a
                                href="{{ route('admin.pendaftar.show', $item->id) }}"
                                class="font-semibold text-slate-900 dark:text-slate-100 hover:text-cyan-600 dark:hover:text-cyan-400 transition"
                            >
                                {{ $item->nama_lengkap }}
                            </a>

                            @if ($item->tipe === 'kelompok')
                                <div class="mt-1">
                                    <x-admin.badge-kelompok :pendaftaran="$item" />
                                </div>
                            @endif

                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                {{ $item->kategori ?? '-' }} • {{ $item->instansi ?? '-' }}
                            </p>
                        </td>

                        {{-- INSTANSI TUJUAN & PERIODE --}}
                        <td class="p-4 px-6 align-top">
                            <p class="text-slate-700 dark:text-slate-200">
                                {{ $item->dinas_tujuan ?? '-' }}
                            </p>

                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                {{ $item->divisi ?? '-' }}
                            </p>

                            <p class="text-sm text-slate-400 dark:text-slate-500 mt-1 whitespace-nowrap">
                                {{ $tgl($item->tanggal_mulai) }}
                                &ndash;
                                {{ $tgl($item->tanggal_selesai) }}
                            </p>
                        </td>

                        {{-- STATUS VERIFIKASI --}}
                        <td class="p-4 px-6 align-top">
                            <x-admin.status-select
                                :status="$item->status"
                                :action="route('admin.verifikasi.update', $item->id)"
                                :alasan-penolakan="$item->alasan_penolakan"
                                :alasan-revisi="$item->alasan_revisi"
                            />
                        </td>

                        {{-- DETAIL --}}
                        <td class="p-4 px-6 align-top text-center">
                            <a
                                href="{{ route('admin.pendaftar.show', $item->id) }}"
                                class="inline-flex items-center justify-center px-3.5 py-2
                                       bg-slate-100 hover:bg-cyan-50 text-slate-700 hover:text-cyan-700
                                       dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 dark:hover:text-white
                                       rounded-xl text-sm font-semibold transition"
                            >
                                Detail Verifikasi
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-12 text-center text-slate-400 dark:text-slate-500 font-semibold">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="text-3xl">📭</span>

                                <span>
                                    {{ request('search')
                                        ? 'Tidak ada hasil untuk pencarian ini.'
                                        : 'Tidak ada berkas yang perlu diverifikasi saat ini.' }}
                                </span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>

    {{-- PAGINATION --}}
    @if (isset($pendaftars) && method_exists($pendaftars, 'links'))
        <div>
            {{ $pendaftars->links() }}
        </div>
    @endif

@endsection
