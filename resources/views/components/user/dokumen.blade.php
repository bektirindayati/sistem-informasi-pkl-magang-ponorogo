{{-- <x-user.dokumen jenis="balasan|selesai|sertifikat" :pendaftaran="$pendaftaran" /> --}}
@props(['pendaftaran', 'jenis'])

@php
    $p = $pendaftaran;

    $c = [
        'balasan'    => ['judul' => 'Surat balasan', 'status' => $p->statusSurat(), 'rute' => 'user.pendaftaran.surat'],
        'selesai'    => ['judul' => 'Surat selesai magang', 'status' => $p->statusSuratSelesai(), 'rute' => 'user.pendaftaran.surat-selesai'],
        'sertifikat' => ['judul' => 'Sertifikat', 'status' => $p->statusSertifikat(), 'rute' => 'user.pendaftaran.sertifikat'],
    ][$jenis];

    $perOrang = $jenis === 'sertifikat';
@endphp

@if ($c['status'] === 'diterbitkan')
    <div class="mt-4 px-4 py-3 rounded-xl border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">{{ $c['judul'] }} telah diterbitkan</p>

            @if (! $perOrang)
                <div class="flex gap-2">
                    <x-button :href="route($c['rute'], ['pendaftaran' => $p->id, 'unduh' => 1])" variant="success" size="sm">Unduh PDF</x-button>
                    <x-button :href="route($c['rute'], $p->id)" variant="secondary" size="sm" target="_blank">Lihat</x-button>
                </div>
            @elseif ($p->sertifikatFiles->count() > 1)
                <x-button :href="route($c['rute'], ['pendaftaran' => $p->id, 'unduh' => 1])" variant="secondary" size="sm">Unduh Semua (ZIP)</x-button>
            @endif
        </div>

        @if ($perOrang)
            @if ($p->tipe === 'kelompok')
                <p class="mt-0.5 text-xs text-emerald-700 dark:text-emerald-300/90">
                    Setiap anggota memiliki sertifikat sendiri. Unduh, lalu bagikan kepada masing-masing anggota.
                </p>
            @endif

            <ul class="mt-2 divide-y divide-emerald-200/70 dark:divide-emerald-500/20">
                @foreach ($p->pesertaSertifikat() as $x)
                    <li class="flex flex-wrap items-center justify-between gap-x-3 gap-y-1.5 py-2.5">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                {{ $x['nama'] }}
                                @if ($x['peran'])<span class="ml-1 text-xs font-medium text-slate-500 dark:text-slate-400">{{ $x['peran'] }}</span>@endif
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{ $x['nim'] }}@if ($x['jurusan']) · {{ $x['jurusan'] }}@endif
                            </p>
                        </div>

                        @if ($x['file'])
                            <span class="flex shrink-0 gap-2">
                                <x-button :href="route($c['rute'], ['pendaftaran' => $p->id, 'berkas' => $x['file']->id, 'unduh' => 1])" variant="success" size="sm">Unduh</x-button>
                                <x-button :href="route($c['rute'], ['pendaftaran' => $p->id, 'berkas' => $x['file']->id])" variant="secondary" size="sm" target="_blank">Lihat</x-button>
                            </span>
                        @else
                            <span class="text-xs text-amber-700 dark:text-amber-300">Belum tersedia</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

@elseif ($c['status'] === 'diproses')
    <div class="mt-4 px-4 py-3 rounded-xl border border-amber-200 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-500/10">
        <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">{{ $c['judul'] }} sedang diproses</p>
        <p class="text-xs text-amber-700 dark:text-amber-300/90 mt-0.5 leading-relaxed">
            Dinas sedang menyiapkan dan menandatangani dokumen ini. Tombol unduh muncul di sini setelah diterbitkan.
        </p>
    </div>

@elseif ($jenis === 'sertifikat' && $p->status === 'diterima')
    <p class="mt-4 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-800/60
              text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
        Sertifikat dan surat selesai magang tersedia setelah masa magang selesai
        @if ($p->tanggal_selesai)({{ \Carbon\Carbon::parse($p->tanggal_selesai)->translatedFormat('d M Y') }})@endif.
    </p>
@endif