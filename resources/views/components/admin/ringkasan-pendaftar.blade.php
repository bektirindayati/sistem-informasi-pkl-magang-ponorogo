@props(['pendaftaran'])

@php
    $p        = $pendaftaran;
    $kelompok = $p->tipe === 'kelompok';
    $tgl      = fn ($d) => $d ? \Carbon\Carbon::parse($d)->translatedFormat('d M Y') : '-';

    $data = [
        'Asal Instansi'         => $p->instansi,
        'Dinas Tujuan'          => $p->dinas?->nama_dinas ?? $p->dinas_tujuan,
        'Bidang'                => $p->bidang?->nama_bidang ?? $p->divisi,
        'Periode Magang'        => $tgl($p->tanggal_mulai) . ' – ' . $tgl($p->tanggal_selesai),
        'Tanggal Surat'         => $tgl($p->tanggal_surat_pengantar),
        'Pengirim Surat'        => $p->jabatan_penerima,
        'Kota Kampus / Sekolah' => $p->kota_tujuan,
    ];

    // Ketua selalu baris pertama, anggota menyusul.
$suratUrl = fn ($path) => filled($path) ? asset('storage/' . $path) : null;

$kolomPeserta = \App\Models\AnggotaPendaftaran::KOLOM;
$peserta = $p->anggotas
    ->map(fn ($a) => [
        'peran' => 'Anggota',
        'surat' => $suratUrl($a->surat_pengantar),
    ] + $a->only($kolomPeserta))
    ->prepend([
        'peran' => 'Ketua',
        'surat' => $suratUrl($p->surat_pengantar),
    ] + $p->only($kolomPeserta))
    ->values();

    $label = 'text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400';
    $isi   = 'mt-0.5 text-sm font-medium text-slate-700 dark:text-slate-200 break-words';
@endphp

<div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900
            shadow-sm dark:shadow-none overflow-hidden">

    {{-- Judul --}}
    <div class="flex flex-wrap items-start justify-between gap-3 px-5 md:px-6 py-5">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $p->nama_lengkap }}</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ $p->kategori ?: '-' }}
                &bull; {{ $kelompok ? 'Berkelompok, ' . $peserta->count() . ' orang' : 'Mandiri' }}
                &bull; Diajukan {{ $p->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}
            </p>
        </div>
        <x-status-badge :status="$p->status" />
    </div>

    {{-- Data pengajuan --}}
    <dl class="grid grid-cols-2 xl:grid-cols-4 gap-x-6 gap-y-4 px-5 md:px-6 py-5
               bg-slate-50/70 dark:bg-slate-950/40 border-y border-slate-200 dark:border-slate-800">
        @foreach ($data as $nama => $nilai)
            <div>
                <dt class="{{ $label }}">{{ $nama }}</dt>
                <dd class="{{ $isi }}">{{ $nilai ?: '-' }}</dd>
            </div>
        @endforeach
    </dl>

    {{-- Berkas --}}
    <div class="flex flex-wrap items-center gap-2 px-5 md:px-6 py-4 border-b border-slate-200 dark:border-slate-800">
        <span class="{{ $label }} mr-1">Berkas</span>
        @foreach (['proposal' => 'Proposal'] as $kolom => $nama)
            @if (filled($p->{$kolom}))
                <a href="{{ asset('storage/' . $p->{$kolom}) }}" target="_blank" rel="noopener"
                   class="inline-flex h-8 items-center px-3 rounded-lg text-xs font-bold transition
                          bg-blue-50 text-blue-700 hover:bg-blue-100
                          dark:bg-cyan-500/10 dark:text-cyan-300 dark:hover:bg-cyan-500/20">{{ $nama }}</a>
            @else
                <span class="inline-flex h-8 items-center px-3 rounded-lg text-xs font-semibold
                             bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">{{ $nama }}: tidak ada</span>
            @endif
        @endforeach
    </div>

    {{-- Peserta --}}
    <div class="px-5 md:px-6 py-4">
        <p class="{{ $label }}">{{ $kelompok ? 'Peserta (' . $peserta->count() . ')' : 'Peserta' }}</p>

        <div class="mt-2 overflow-x-auto">
            <table class="w-full min-w-[760] text-left border-collapse">
               <thead>
    <tr class="border-b border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-300">
        <th class="py-2 pr-4 w-10">No</th>
        <th class="py-2 pr-4">Nama</th>
        @if ($kelompok)
            <th class="py-2 pr-4">Peran</th>
        @endif
        <th class="py-2 pr-4">NIM / NISN</th>
        <th class="py-2 pr-4">Jurusan</th>
        <th class="py-2 pr-4">Asal Instansi</th>
        <th class="py-2 pr-4">Surat Pengantar</th>
        <th class="py-2">No. HP</th>
    </tr>
</thead>

<tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-300">
    @foreach ($peserta as $o)
        <tr>
            <td class="py-2.5 pr-4">{{ $loop->iteration }}</td>
            <td class="py-2.5 pr-4 font-semibold text-slate-800 dark:text-slate-100">
                {{ $o['nama_lengkap'] }}
            </td>
            @if ($kelompok)
                <td class="py-2.5 pr-4">{{ $o['peran'] }}</td>
            @endif
            <td class="py-2.5 pr-4">{{ $o['nim_nisn'] ?: '-' }}</td>
            <td class="py-2.5 pr-4">{{ $o['jurusan'] ?: '-' }}</td>
            <td class="py-2.5 pr-4">{{ $o['instansi'] ?: '-' }}</td>
            <td class="py-2.5 pr-4">
                <span class="block text-sm text-slate-500 dark:text-slate-400">
                    {{ $o['nomor_surat_pengantar'] ?: '-' }}
                </span>

                @if ($o['surat'])
                    <a href="{{ $o['surat'] }}" target="_blank" rel="noopener"
                       class="mt-1 inline-flex rounded-md border border-blue-200 bg-blue-50 px-2 py-0.5 text-xs font-semibold text-blue-700
                              hover:bg-blue-100 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300">
                        Lihat surat
                    </a>
                @else
                    <span class="mt-1 block text-xs text-amber-600 dark:text-amber-300">
                        Belum diunggah
                    </span>
                @endif
            </td>
            <td class="py-2.5">{{ $o['no_hp'] ?: '-' }}</td>
        </tr>
    @endforeach
</tbody>
            </table>
        </div>
    </div>
</div>