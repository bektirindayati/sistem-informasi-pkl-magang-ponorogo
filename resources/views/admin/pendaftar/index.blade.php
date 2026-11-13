@extends('admin.layout.app')

@section('page-title', 'Data Seluruh Pendaftar')

@section('content')

    @php
        $thead = 'bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 font-bold text-sm uppercase tracking-wide';
        $tbody = 'divide-y divide-slate-100 dark:divide-slate-800 text-base text-slate-600 dark:text-slate-300';
        $tgl   = fn ($d) => $d ? \Carbon\Carbon::parse($d)->translatedFormat('d M Y') : '-';

        $badge = [
            'pending'  => 'bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300',
            'diterima' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300',
            'revisi'   => 'bg-orange-50 text-orange-700 dark:bg-orange-500/15 dark:text-orange-300',
            'ditolak'  => 'bg-rose-50 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300',
            'selesai'  => 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
        ];
        $label = [
            'pending' => 'Menunggu Verifikasi', 'revisi' => 'Perlu Revisi', 'diterima' => 'Diterima',
            'ditolak' => 'Ditolak', 'selesai' => 'Selesai Magang',
        ];

        $seksi = [
            [
                'kunci' => 'aktif', 'aksen' => 'border-l-emerald-500',
                'title' => 'Pemagang Aktif / Proses',
                'description' => 'Peserta yang sedang menjalani magang atau dalam tahap verifikasi.',
                'data' => $pemagangAktif ?? [], 'kosong' => 'Tidak ada pemagang aktif saat ini.',
            ],
            [
                'kunci' => 'selesai', 'aksen' => 'border-l-slate-400',
                'title' => 'Riwayat / Pemagang Selesai',
                'description' => 'Arsip peserta yang telah diterima dan masa magangnya sudah selesai.',
                'data' => $pemagangSelesai ?? [], 'kosong' => 'Belum ada riwayat pemagang selesai.',
            ],
            [
                'kunci' => 'ditolak', 'aksen' => 'border-l-rose-500',
                'title' => 'Pemagang Ditolak',
                'description' => 'Arsip peserta yang pengajuan magang atau PKL-nya ditolak.',
                'data' => $pemagangDitolak ?? [], 'kosong' => 'Belum ada pendaftar yang ditolak.',
            ],
        ];

        $total = collect($seksi)->sum(fn ($s) => count($s['data']));
    @endphp

    {{-- PENCARIAN: menyaring ketiga tabel saat mengetik --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900
                shadow-sm dark:shadow-none p-4 flex flex-wrap items-center gap-x-4 gap-y-2">
        <input type="search" id="cari" autocomplete="off"
               placeholder="Ketik nama atau NIM, termasuk anggota kelompok..."
               class="flex-1 min-w-[14rem] px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700
                      bg-white dark:bg-slate-950/60 text-base text-slate-700 dark:text-slate-100
                      placeholder:text-slate-400 dark:placeholder:text-slate-500
                      focus:outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 dark:focus:ring-cyan-500/20">
        <p class="text-sm text-slate-500 dark:text-slate-400">
            <span id="hitungTampil" class="font-semibold text-slate-700 dark:text-slate-200">{{ $total }}</span>
            dari {{ $total }} pengajuan
        </p>
    </div>

    @foreach ($seksi as $s)
        <x-admin.table-card :title="$s['title']" :description="$s['description']"
                            class="border-l-4 {{ $s['aksen'] }}" data-seksi="{{ $s['kunci'] }}">

            <x-slot:actions>
                <span data-hitung
                      class="px-3 py-1 rounded-full text-sm font-semibold bg-slate-100 text-slate-700
                             dark:bg-slate-800 dark:text-slate-200">{{ count($s['data']) }}</span>
            </x-slot:actions>

            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="{{ $thead }}">
                        <th class="p-4 px-6">Nama Pendaftar</th>
                        <th class="p-4 px-6">Asal Instansi / Sekolah</th>
                        <th class="p-4 px-6">Periode Magang</th>
                        <th class="p-4 px-6">Status</th>
                        <th class="p-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="{{ $tbody }}">
                    @foreach ($s['data'] as $item)
                        @php
                            $cari = \Illuminate\Support\Str::lower(
                                collect([$item->nama_lengkap, $item->nim_nisn, $item->instansi])
                                    ->merge($item->anggotas->flatMap(fn ($a) => [$a->nama_lengkap, $a->nim_nisn]))
                                    ->filter()->implode(' ')
                            );
                        @endphp

                        <tr data-cari="{{ $cari }}" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                            <td class="p-4 px-6">
                                <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->nama_lengkap ?? '-' }}</span>
                                @if ($item->tipe === 'kelompok')
                                    <div class="mt-1"><x-admin.badge-kelompok :pendaftaran="$item" /></div>
                                @endif
                            </td>

                            <td class="p-4 px-6">{{ $item->instansi ?? '-' }}</td>

                            <td class="p-4 px-6 text-sm text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                {{ $tgl($item->tanggal_mulai) }} – {{ $tgl($item->tanggal_selesai) }}
                            </td>

                            <td class="p-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-sm font-semibold whitespace-nowrap {{ $badge[$item->status] ?? $badge['pending'] }}">
                                    {{ $label[$item->status] ?? ucfirst($item->status) }}
                                </span>
                            </td>

                            <td class="p-4 px-6 text-center">
                                <a href="{{ route('admin.pendaftar.show', $item->id) }}"
                                   class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700
                                          dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200
                                          rounded-lg text-sm font-semibold transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach

                    @if (count($s['data']) === 0)
                        <tr data-kosong>
                            <td colspan="5" class="p-8 text-center text-slate-400 dark:text-slate-500 font-semibold">{{ $s['kosong'] }}</td>
                        </tr>
                    @endif

                    <tr data-hasil-kosong class="hidden">
                        <td colspan="5" class="p-8 text-center text-slate-400 dark:text-slate-500 font-semibold">
                            Tidak ada hasil untuk pencarian ini.
                        </td>
                    </tr>
                </tbody>
            </table>
        </x-admin.table-card>
    @endforeach

@endsection

@push('scripts')
<script>
    (() => {
        const input  = document.getElementById('cari');
        const tampil = document.getElementById('hitungTampil');
        const seksi  = [...document.querySelectorAll('[data-seksi]')];

        const saring = () => {
            const kata = input.value.toLowerCase().split(/\s+/).filter(Boolean);
            let total = 0;

            seksi.forEach(s => {
                let cocok = 0;

                s.querySelectorAll('tr[data-cari]').forEach(tr => {
                    const ok = kata.every(k => tr.dataset.cari.includes(k));
                    tr.classList.toggle('hidden', !ok);
                    if (ok) cocok++;
                });

                s.querySelector('[data-hitung]').textContent = cocok;
                s.querySelector('[data-kosong]')?.classList.toggle('hidden', kata.length > 0);
                s.querySelector('[data-hasil-kosong]').classList.toggle('hidden', kata.length === 0 || cocok > 0);
                total += cocok;
            });

            tampil.textContent = total;
        };

        input.addEventListener('input', saring);
        saring();   // juga menangani isian yang dipulihkan browser
    })();
</script>
@endpush