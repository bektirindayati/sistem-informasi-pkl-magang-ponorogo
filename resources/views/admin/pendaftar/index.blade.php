@extends('admin.layout.app')

@section('page-title', 'Data Seluruh Pendaftar')

@section('content')

    @php
        $tgl = fn ($d) => $d ? \Carbon\Carbon::parse($d)->translatedFormat('d M Y') : null;

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

                $seksi = $filter ? [[
            'kunci' => 'filter', 'aksen' => 'border-l-blue-500',
            'title' => $filter['judul'], 'description' => $filter['keterangan'],
            'data' => $daftarFilter, 'kosong' => 'Tidak ada pendaftar pada daftar ini.',
        ]] : [
            [
                'kunci' => 'aktif', 'title' => 'Aktif / Proses',
                'description' => 'Peserta yang sedang menjalani magang atau dalam tahap verifikasi.',
                'data' => $pemagangAktif ?? [], 'kosong' => 'Tidak ada pemagang aktif saat ini.',
            ],
            [
                'kunci' => 'selesai', 'title' => 'Selesai',
                'description' => 'Arsip peserta yang telah diterima dan masa magangnya sudah selesai.',
                'data' => $pemagangSelesai ?? [], 'kosong' => 'Belum ada riwayat pemagang selesai.',
            ],
            [
                'kunci' => 'ditolak', 'title' => 'Ditolak',
                'description' => 'Arsip peserta yang pengajuan magang atau PKL-nya ditolak.',
                'data' => $pemagangDitolak ?? [], 'kosong' => 'Belum ada pendaftar yang ditolak.',
            ],
        ];

                $total = collect($seksi)->sum(fn ($s) => count($s['data']));
    @endphp

    <section class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800
                    shadow-sm dark:shadow-none overflow-hidden">

        {{-- HEADER: judul + pencarian (menyaring semua tab saat mengetik) --}}
        <div class="px-6 py-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">Data Pendaftar</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    <span id="hitungTampil" class="font-semibold text-slate-700 dark:text-slate-200">{{ $total }}</span>
                    dari {{ $total }} pengajuan
                </p>
            </div>

            <div class="relative lg:w-[420px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 10.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z"/>
                </svg>
                <input type="search" id="cari" autocomplete="off"
                       placeholder="Ketik nama atau NIM, termasuk anggota kelompok..."
                       class="w-full h-10 pl-9 pr-3 rounded-xl border border-slate-200 dark:border-slate-700
                              bg-white dark:bg-slate-950/60 text-sm text-slate-700 dark:text-slate-100
                              placeholder:text-slate-400 dark:placeholder:text-slate-500
                              focus:outline-none focus:border-cyan-600 focus:ring-2 focus:ring-cyan-100 dark:focus:ring-cyan-500/20">
            </div>
        </div>

        {{-- TAB --}}
        <div class="px-6 flex gap-6 border-b border-slate-200 dark:border-slate-800 overflow-x-auto" role="tablist">
            @foreach ($seksi as $s)
                <button type="button" role="tab" data-tab="{{ $s['kunci'] }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        class="-mb-px flex items-center gap-2 whitespace-nowrap border-b-2 border-transparent py-3 text-sm font-semibold
                               text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white transition
                               aria-selected:border-cyan-600 aria-selected:text-cyan-700
                               dark:aria-selected:border-cyan-400 dark:aria-selected:text-cyan-300">
                    {{ $s['title'] }}
                    <span data-hitung class="px-2 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600
                                             dark:bg-slate-800 dark:text-slate-300">{{ count($s['data']) }}</span>
                </button>
            @endforeach
        </div>

        {{-- PANEL TIAP TAB --}}
        @foreach ($seksi as $s)
            <div data-seksi="{{ $s['kunci'] }}" role="tabpanel" class="{{ $loop->first ? '' : 'hidden' }}">
                <p class="px-6 py-3 text-sm text-slate-500 dark:text-slate-400">{{ $s['description'] }}</p>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[720px]">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-950/50 border-y border-slate-200 dark:border-slate-800
                                       text-slate-600 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                                <th class="py-3 px-6">Nama Pendaftar</th>
                                <th class="py-3 px-6">Asal Instansi / Sekolah</th>
                                <th class="py-3 px-6">Periode Magang</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-300">
                            @foreach ($s['data'] as $item)
                                @php
                                    $cari = \Illuminate\Support\Str::lower(
                                        collect([$item->nama_lengkap, $item->nim_nisn, $item->instansi])
                                            ->merge($item->anggotas->flatMap(fn ($a) => [$a->nama_lengkap, $a->nim_nisn]))
                                            ->filter()->implode(' ')
                                    );
                                    $mulai = $tgl($item->tanggal_mulai);
                                    $akhir = $tgl($item->tanggal_selesai);
                                @endphp

                                <tr data-cari="{{ $cari }}" class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                                    <td class="py-4 px-6">
                                        <div class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->nama_lengkap ?? '-' }}</div>
                                        @if ($item->nim_nisn)
                                            <div class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ $item->nim_nisn }}</div>
                                        @endif
                                        @if ($item->tipe === 'kelompok')
                                            <div class="mt-1"><x-admin.badge-kelompok :pendaftaran="$item" /></div>
                                        @endif
                                    </td>

                                    <td class="py-4 px-6">{{ $item->instansi ?? '-' }}</td>

                                    <td class="py-4 px-6 whitespace-nowrap text-slate-500 dark:text-slate-400">
                                        {{ ($mulai || $akhir) ? ($mulai ?? '-') . ' – ' . ($akhir ?? '-') : '-' }}
                                    </td>

                                    <td class="py-4 px-6">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $badge[$item->status] ?? $badge['pending'] }}">
                                            {{ $label[$item->status] ?? ucfirst($item->status) }}
                                        </span>
                                    </td>

                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ route('admin.pendaftar.show', $item->id) }}"
                                           class="inline-flex h-8 items-center px-3 rounded-lg text-xs font-bold transition
                                                  bg-slate-100 hover:bg-slate-200 text-slate-700
                                                  dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200">
                                            Detail
                                        </a>
                                    </td>
                                </tr>

                                {{-- Anggota kelompok: tersembunyi, baru tampil saat pencarian cocok --}}
                                @if ($item->tipe === 'kelompok' && $item->anggotas->isNotEmpty())
                                    <tr data-anggota class="hidden !border-t-0">
                                        <td colspan="5" class="px-6 pb-4 pt-0">
                                            <div class="rounded-xl border border-slate-200 dark:border-slate-800
                                                        bg-slate-50/70 dark:bg-slate-950/40 px-4 py-3">
                                                <p class="text-xs font-bold text-slate-500 dark:text-slate-400">
                                                    Anggota kelompok ({{ $item->anggotas->count() }})
                                                </p>

                                                <ul class="mt-2 divide-y divide-slate-200/70 dark:divide-slate-800">
                                                    @foreach ($item->anggotas as $a)
                                                        <li data-nama-anggota="{{ \Illuminate\Support\Str::lower($a->nama_lengkap . ' ' . $a->nim_nisn) }}"
                                                            class="-mx-2 grid grid-cols-1 gap-x-4 gap-y-0.5 rounded-lg px-2 py-2 text-xs
                                                                   text-slate-500 dark:text-slate-400 sm:grid-cols-4">
                                                            <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $a->nama_lengkap }}</span>
                                                            <span>{{ $a->nim_nisn ?: '-' }}</span>
                                                            <span>{{ $a->jurusan ?: '-' }}</span>
                                                            <span>{{ $a->instansi ?: '-' }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            @if (count($s['data']) === 0)
                                <tr data-kosong>
                                    <td colspan="5" class="py-12 px-6 text-center text-slate-400 dark:text-slate-500 font-semibold">{{ $s['kosong'] }}</td>
                                </tr>
                            @endif

                            <tr data-hasil-kosong class="hidden">
                                <td colspan="5" class="py-12 px-6 text-center text-slate-400 dark:text-slate-500 font-semibold">
                                    Tidak ada hasil untuk pencarian ini di tab ini.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </section>

@endsection

@push('scripts')
<script>
    (() => {
        const input  = document.getElementById('cari');
        const tampil = document.getElementById('hitungTampil');
        const tabs   = [...document.querySelectorAll('[data-tab]')];
        const seksi  = [...document.querySelectorAll('[data-seksi]')];

        const pilihTab = kunci => {
            tabs.forEach(t => t.setAttribute('aria-selected', t.dataset.tab === kunci ? 'true' : 'false'));
            seksi.forEach(s => s.classList.toggle('hidden', s.dataset.seksi !== kunci));
        };

        const saring = () => {
            const kata = input.value.toLowerCase().split(/\s+/).filter(Boolean);
            let total = 0;

            seksi.forEach(s => {
                let cocok = 0;

                s.querySelectorAll('tr[data-cari]').forEach(tr => {
                    const ok = kata.every(k => tr.dataset.cari.includes(k));
                    tr.classList.toggle('hidden', !ok);
                    if (ok) cocok++;

                    // Anggota kelompok: tampil hanya saat sedang mencari dan baris ini cocok.
                    const anggota = tr.nextElementSibling?.matches('[data-anggota]') ? tr.nextElementSibling : null;
                    if (!anggota) return;

                    anggota.classList.toggle('hidden', !(ok && kata.length));
                    anggota.querySelectorAll('[data-nama-anggota]').forEach(li => {
                        const sorot = kata.length > 0 && kata.some(k => li.dataset.namaAnggota.includes(k));
                        li.classList.toggle('bg-cyan-50', sorot);
                        li.classList.toggle('dark:bg-cyan-500/10', sorot);
                    });
                });

                tabs.find(t => t.dataset.tab === s.dataset.seksi).querySelector('[data-hitung]').textContent = cocok;
                s.querySelector('[data-kosong]')?.classList.toggle('hidden', kata.length > 0);
                s.querySelector('[data-hasil-kosong]').classList.toggle('hidden', kata.length === 0 || cocok > 0);
                total += cocok;
            });

            tampil.textContent = total;
        };

        tabs.forEach(t => t.addEventListener('click', () => pilihTab(t.dataset.tab)));
        input.addEventListener('input', saring);
        saring();   // juga menangani isian yang dipulihkan browser
    })();
</script>
@endpush