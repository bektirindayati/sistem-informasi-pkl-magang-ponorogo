@props(['pendaftaran'])

@php
    $tgl = fn ($nilai) => $nilai ? \Carbon\Carbon::parse($nilai)->translatedFormat('d M Y') : '-';
    $kelompok = $pendaftaran->tipe === 'kelompok';

    $dataDiri = [
        'Jenis Pendaftaran' => \App\Models\PendaftaranMagang::TIPE[$pendaftaran->tipe] ?? '-',
        'Nama Lengkap'      => $pendaftaran->nama_lengkap,
        'Kategori'          => $pendaftaran->kategori,
        'NIM / NISN'        => $pendaftaran->nim_nisn,
        'No. WhatsApp / HP' => $pendaftaran->no_hp,
        'Asal Instansi'     => $pendaftaran->instansi,
        'Jurusan'           => $pendaftaran->jurusan,
    ];

    $labelAnggota = [
        'nama_lengkap' => 'Nama Lengkap',
        'nim_nisn'     => 'NIM / NISN',
        'instansi'     => 'Asal Instansi',
        'jurusan'      => 'Jurusan',
        'no_hp'        => 'No. WhatsApp / HP',
    ];

    $instansiPeriode = [
        'Dinas Tujuan'    => $pendaftaran->dinas?->nama_dinas ?? $pendaftaran->dinas_tujuan,
        'Bidang'          => $pendaftaran->bidang?->nama_bidang ?? $pendaftaran->divisi,
        'Tanggal Mulai'   => $tgl($pendaftaran->tanggal_mulai),
        'Tanggal Selesai' => $tgl($pendaftaran->tanggal_selesai),
    ];

    $kartu = 'rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm dark:shadow-none p-6';
    $judul = 'text-lg font-bold text-slate-900 dark:text-white';
    $kecil = 'text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400';
    $isi   = 'mt-1 text-base font-medium text-slate-600 dark:text-slate-300 break-words';
    $grid  = 'grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4';
@endphp

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">

    <div class="xl:col-span-2 space-y-6">

        {{-- DATA DIRI / KETUA KELOMPOK --}}
        <div class="{{ $kartu }}">
            <h3 class="{{ $judul }}">{{ $kelompok ? 'Data Ketua Kelompok' : 'Data Diri & Akademik' }}</h3>

            <dl class="mt-5 {{ $grid }}">
                @foreach ($dataDiri as $nama => $nilai)
                    <div>
                        <dt class="{{ $kecil }}">{{ $nama }}</dt>
                        <dd class="{{ $isi }}">{{ $nilai ?: '-' }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>

        {{-- ANGGOTA KELOMPOK --}}
        @if ($kelompok)
            <div class="{{ $kartu }}">
                <h3 class="{{ $judul }}">Anggota Kelompok ({{ $pendaftaran->anggotas->count() }})</h3>

                <div class="mt-4 space-y-3">
                    @forelse ($pendaftaran->anggotas as $a)
                        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 p-4">
                            <p class="{{ $kecil }}">Anggota {{ $loop->iteration }}</p>

                            <dl class="mt-3 {{ $grid }}">
                                @foreach ($labelAnggota as $kolom => $label)
                                    <div>
                                        <dt class="{{ $kecil }}">{{ $label }}</dt>
                                        <dd class="{{ $isi }}">{{ $a->{$kolom} ?: '-' }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    @empty
                        <p class="text-base text-slate-500 dark:text-slate-400">Belum ada anggota.</p>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- INSTANSI TUJUAN & PERIODE --}}
        <div class="{{ $kartu }}">
            <h3 class="{{ $judul }}">Instansi Tujuan &amp; Periode</h3>

            <dl class="mt-5 {{ $grid }}">
                @foreach ($instansiPeriode as $nama => $nilai)
                    <div>
                        <dt class="{{ $kecil }}">{{ $nama }}</dt>
                        <dd class="{{ $isi }}">{{ $nilai ?: '-' }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>

        {{-- BERKAS TERLAMPIR --}}
        <div class="{{ $kartu }}">
            <h3 class="{{ $judul }}">Berkas Terlampir</h3>

            <div class="mt-4 flex flex-wrap gap-2">
                @foreach (['surat_pengantar' => 'Surat Pengantar', 'proposal' => 'Proposal'] as $kolom => $nama)
                    @if (filled($pendaftaran->{$kolom}))
                        <a href="{{ asset('storage/' . $pendaftaran->{$kolom}) }}" target="_blank" rel="noopener"
                           class="px-4 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700
                                  dark:bg-blue-500/10 dark:hover:bg-blue-500/20 dark:text-blue-300
                                  text-sm font-semibold transition">
                            {{ $nama }}
                        </a>
                    @else
                        <span class="px-4 py-2 rounded-xl bg-slate-100 text-slate-400
                                     dark:bg-slate-800 dark:text-slate-500 text-sm font-medium">
                            Tidak ada {{ strtolower($nama) }}
                        </span>
                    @endif
                @endforeach
            </div>
        </div>

    </div>

    {{-- INFORMASI PENGAJUAN --}}
    <div class="space-y-6">
        <div class="{{ $kartu }} space-y-2 text-sm text-slate-500 dark:text-slate-400">
            <p>Diajukan: {{ $pendaftaran->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
            <p>Terakhir diperbarui: {{ $pendaftaran->updated_at?->translatedFormat('d M Y, H:i') ?? '-' }}</p>
        </div>
    </div>

</div>