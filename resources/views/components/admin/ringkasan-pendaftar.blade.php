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
        'Nomor Surat Pengantar' => $p->nomor_surat_pengantar,
        'Tanggal Surat'         => $tgl($p->tanggal_surat_pengantar),
        'Pengirim Surat'        => $p->jabatan_penerima,
        'Kota Kampus / Sekolah' => $p->kota_tujuan,
    ];

    // Ketua selalu baris pertama, anggota menyusul.
    $kolomPeserta = \App\Models\AnggotaPendaftaran::KOLOM;
    $peserta = $p->anggotas
        ->map(fn ($a) => ['peran' => 'Anggota'] + $a->only($kolomPeserta))
        ->prepend(['peran' => 'Ketua'] + $p->only($kolomPeserta))
        ->values();

    $label = 'text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400';
    $isi   = 'mt-0.5 text-base text-slate-600 dark:text-slate-300 break-words';
@endphp

<div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900
            shadow-sm dark:shadow-none p-5 md:p-6">

    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $p->nama_lengkap }}</h2>
            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                {{ $p->kategori ?: '-' }}
                · {{ $kelompok ? 'Berkelompok, ' . $peserta->count() . ' orang' : 'Mandiri' }}
                · Diajukan {{ $p->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}
            </p>
        </div>
        <x-status-badge :status="$p->status" />
    </div>

    <dl class="mt-4 grid grid-cols-2 xl:grid-cols-4 gap-x-6 gap-y-3">
        @foreach ($data as $nama => $nilai)
            <div>
                <dt class="{{ $label }}">{{ $nama }}</dt>
                <dd class="{{ $isi }}">{{ $nilai ?: '-' }}</dd>
            </div>
        @endforeach
    </dl>

    <p class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-700 text-base">
        <span class="{{ $label }} mr-2">Berkas</span>
        @foreach (['surat_pengantar' => 'Surat Pengantar', 'proposal' => 'Proposal'] as $kolom => $nama)
            @if (filled($p->{$kolom}))
                <a href="{{ asset('storage/' . $p->{$kolom}) }}" target="_blank" rel="noopener"
                   class="mr-4 font-semibold text-blue-600 hover:underline dark:text-cyan-400">{{ $nama }}</a>
            @else
                <span class="mr-4 text-slate-400 dark:text-slate-500">{{ $nama }}: tidak ada</span>
            @endif
        @endforeach
    </p>

    <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-700">
        <p class="{{ $label }}">{{ $kelompok ? 'Peserta (' . $peserta->count() . ')' : 'Peserta' }}</p>

        <div class="mt-2 overflow-x-auto">
            <table class="w-full min-w-[620px] text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-700 text-sm font-bold text-slate-700 dark:text-slate-200">
                        <th class="py-2 pr-4 w-10">No</th>
                        <th class="py-2 pr-4">Nama</th>
                        @if ($kelompok)<th class="py-2 pr-4">Peran</th>@endif
                        <th class="py-2 pr-4">NIM / NISN</th>
                        <th class="py-2 pr-4">Jurusan</th>
                        <th class="py-2 pr-4">Asal Instansi</th>
                        <th class="py-2">No. HP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-base text-slate-600 dark:text-slate-300">
                    @foreach ($peserta as $o)
                        <tr>
                            <td class="py-2 pr-4">{{ $loop->iteration }}</td>
                            <td class="py-2 pr-4 font-semibold text-slate-800 dark:text-slate-100">{{ $o['nama_lengkap'] }}</td>
                            @if ($kelompok)<td class="py-2 pr-4">{{ $o['peran'] }}</td>@endif
                            <td class="py-2 pr-4">{{ $o['nim_nisn'] ?: '-' }}</td>
                            <td class="py-2 pr-4">{{ $o['jurusan'] ?: '-' }}</td>
                            <td class="py-2 pr-4">{{ $o['instansi'] ?: '-' }}</td>
                            <td class="py-2">{{ $o['no_hp'] ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>