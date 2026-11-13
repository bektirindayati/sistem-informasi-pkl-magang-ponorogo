{{-- <x-user.dokumen jenis="balasan|selesai|sertifikat" :pendaftaran="$p" /> --}}
@props(['pendaftaran', 'jenis'])

@php
    $p = $pendaftaran;

    $c = [
        'balasan'    => ['judul' => 'Surat balasan', 'status' => $p->statusSurat(), 'rute' => 'user.pendaftaran.surat'],
        'selesai'    => ['judul' => 'Surat selesai magang', 'status' => $p->statusSuratSelesai(), 'rute' => 'user.pendaftaran.surat-selesai'],
        'sertifikat' => ['judul' => 'Sertifikat', 'status' => $p->statusSertifikat(), 'rute' => 'user.pendaftaran.sertifikat'],
    ][$jenis];

    $banyak = $jenis === 'sertifikat';
@endphp

@if ($c['status'] === 'diterbitkan')
    <div class="mt-4 px-4 py-3 rounded-xl border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">{{ $c['judul'] }} telah diterbitkan</p>

            @unless ($banyak)
                <div class="flex gap-2">
                    <x-button :href="route($c['rute'], ['pendaftaran' => $p->id, 'unduh' => 1])" variant="success" size="sm">Unduh PDF</x-button>
                    <x-button :href="route($c['rute'], $p->id)" variant="secondary" size="sm" target="_blank">Lihat</x-button>
                </div>
            @endunless
        </div>

        @if ($banyak)
            @if ($p->tipe === 'kelompok')
                <p class="mt-0.5 text-xs text-emerald-700 dark:text-emerald-300/90">
                    Setiap anggota memiliki sertifikat sendiri. Unduh, lalu bagikan kepada masing-masing anggota.
                </p>
            @endif

            <ul class="mt-2 divide-y divide-emerald-200/70 dark:divide-emerald-500/20">
                @foreach ($p->sertifikatFiles as $f)
                    <li class="flex items-center justify-between gap-3 py-2">
                        <span class="min-w-0 truncate text-sm text-slate-700 dark:text-slate-200">{{ $f->nama }}</span>
                        <span class="flex shrink-0 gap-2">
                            <x-button :href="route($c['rute'], ['pendaftaran' => $p->id, 'berkas' => $f->id, 'unduh' => 1])" variant="success" size="sm">Unduh</x-button>
                            <x-button :href="route($c['rute'], ['pendaftaran' => $p->id, 'berkas' => $f->id])" variant="secondary" size="sm" target="_blank">Lihat</x-button>
                        </span>
                    </li>
                @endforeach
            </ul>

            @if ($p->sertifikatFiles->count() > 1)
                <div class="mt-2">
                    <x-button :href="route($c['rute'], ['pendaftaran' => $p->id, 'unduh' => 1])" variant="secondary" size="sm">Unduh Semua (ZIP)</x-button>
                </div>
            @endif
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