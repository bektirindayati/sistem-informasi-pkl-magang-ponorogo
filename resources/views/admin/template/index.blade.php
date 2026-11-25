@extends('admin.layout.app')

@section('page-title', 'Template Surat')

@section('content')

    @php
        $dinasId = $superAdmin ? null : $dinas?->id;
        $lingkup = $superAdmin ? 'Template Bawaan' : 'Template ' . ($dinas?->nama_dinas ?? 'Dinas');

        $dipilih = array_key_exists((string) request('jenis'), $slot) ? (string) request('jenis') : array_key_first($slot);

        // Status tiap jenis surat (untuk daftar di kiri).
        $ringkas = collect($slot)->map(function ($info, $jenis) use ($milik, $bawaan, $superAdmin) {
            $daftar  = $milik->get($jenis, collect());
            $draf    = $daftar->firstWhere('status', 'draf');
            $aktif   = $daftar->firstWhere('status', 'aktif');
            $warisan = $superAdmin ? null : $bawaan->get($jenis);

            [$label, $warna] = match (true) {
                (bool) $draf    => ['Menunggu aktivasi', 'amber'],
                (bool) $aktif   => [$superAdmin ? 'Template bawaan' : 'Template dinas', 'emerald'],
                (bool) $warisan => ['Template bawaan', 'blue'],
                default         => ['File cadangan', 'slate'],
            };

            return compact('info', 'draf', 'aktif', 'warisan', 'label', 'warna');
        });

        // Jenis yang sedang dibuka.
        $s          = $ringkas[$dipilih];
        $draf       = $s['draf'];
        $aktif      = $s['aktif'];
        $warisan    = $s['warisan'];
        $tampil     = $draf ?? $aktif ?? $warisan;
        $galat      = $errors->getBag($dipilih);
        $bisaKelola = $tampil && (int) $tampil->dinas_id === (int) $dinasId;

        $keadaan = ! $tampil ? 'kosong'
            : ($tampil->pratinjau_status === 'siap' && $tampil->pratinjau ? 'siap'
            : ($tampil->pratinjau_status === 'gagal' ? 'gagal' : 'menunggu'));
        $macet = $keadaan === 'menunggu' && $tampil->updated_at->diffInSeconds(now(), true) > 90;

        $keterangan = $draf ? 'Template baru menunggu aktivasi'
            : ($aktif ? 'Template aktif' : ($warisan ? 'Memakai template bawaan' : 'Memakai file cadangan sistem'));

        $lencana = [
            'amber'   => 'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-300',
            'emerald' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-300',
            'blue'    => 'bg-blue-100 text-blue-800 dark:bg-blue-500/15 dark:text-blue-300',
            'slate'   => 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300',
        ];

        $btn   = 'inline-flex items-center justify-center whitespace-nowrap px-3.5 py-2 rounded-lg border text-sm font-semibold transition';
        $warna = [
            'utama'  => 'border-transparent bg-emerald-600 text-white hover:bg-emerald-700',
            'biru'   => 'border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300 dark:hover:bg-blue-500/20',
            'netral' => 'border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700',
            'ungu'   => 'border-violet-200 bg-violet-50 text-violet-700 hover:bg-violet-100 dark:border-violet-500/30 dark:bg-violet-500/10 dark:text-violet-300 dark:hover:bg-violet-500/20',
            'bahaya' => 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300 dark:hover:bg-rose-500/20',
        ];
        $kartu = 'rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm dark:shadow-none';
        $label = 'text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400';
    @endphp

    @foreach (['success' => 'emerald', 'error' => 'rose'] as $kunci => $warnaPesan)
        @if (session($kunci))
            <div class="mb-4 rounded-xl border border-{{ $warnaPesan }}-200 dark:border-{{ $warnaPesan }}-500/25
                        bg-{{ $warnaPesan }}-50 dark:bg-{{ $warnaPesan }}-500/10 px-4 py-3 text-base font-medium
                        text-{{ $warnaPesan }}-700 dark:text-{{ $warnaPesan }}-300">
                {{ session($kunci) }}
            </div>
        @endif
    @endforeach

    {{-- PENGANTAR --}}
    <div class="{{ $kartu }} p-5 md:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="max-w-2xl">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">Pengaturan</p>
                <h2 class="mt-1 text-2xl font-extrabold text-slate-900 dark:text-white">{{ $lingkup }}</h2>
                <p class="mt-1 text-base text-slate-600 dark:text-slate-300">
                    @if ($superAdmin)
                        Template bawaan dipakai oleh semua dinas yang belum membuat template sendiri.
                    @else
                        Draf surat memakai template dinas ini. Bila belum ada, dipakai template bawaan.
                    @endif
                </p>
            </div>

            <ol class="flex flex-wrap gap-2 text-sm text-slate-600 dark:text-slate-300">
                @foreach (['Unduh dan ubah di Word', 'Unggah dan lihat contoh', 'Aktifkan'] as $i => $langkah)
                    <li class="flex items-center gap-2 rounded-full border border-slate-200 dark:border-slate-700 px-3 py-1.5">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white dark:bg-cyan-600">{{ $i + 1 }}</span>
                        {{ $langkah }}
                    </li>
                @endforeach
            </ol>
        </div>
    </div>

    <div class="grid items-start gap-5 lg:grid-cols-[17rem_minmax(0,1fr)]">

        {{-- DAFTAR JENIS SURAT --}}
        <nav class="flex gap-2 overflow-x-auto pb-1 lg:flex-col lg:overflow-visible lg:pb-0" aria-label="Jenis surat">
            @foreach ($ringkas as $jenis => $r)
                @php $dibuka = $jenis === $dipilih; @endphp

                <a href="{{ route('admin.template.index', ['jenis' => $jenis]) }}"
                   @if ($dibuka) aria-current="page" @endif
                   class="min-w-[13rem] shrink-0 rounded-xl border px-4 py-3 transition lg:min-w-0
                          {{ $dibuka
                              ? 'border-blue-300 bg-blue-50 dark:border-cyan-500/40 dark:bg-cyan-500/10'
                              : 'border-slate-200 bg-white hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:hover:bg-slate-800/60' }}">
                    <p class="text-sm font-semibold {{ $dibuka ? 'text-blue-800 dark:text-cyan-200' : 'text-slate-800 dark:text-slate-100' }}">
                        {{ $r['info']['judul'] }}
                    </p>
                    <span class="mt-1.5 inline-block rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $lencana[$r['warna']] }}">
                        {{ $r['label'] }}
                    </span>
                </a>
            @endforeach
        </nav>

        {{-- PANEL JENIS YANG DIBUKA --}}
        <section class="{{ $kartu }} min-w-0">

            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 dark:border-slate-800 md:px-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $s['info']['judul'] }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $keterangan }}</p>
                </div>

                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $lencana[$s['warna']] }}">{{ $s['label'] }}</span>
            </div>

            <div class="grid gap-6 p-5 md:p-6 xl:grid-cols-5">

                {{-- CONTOH HASIL --}}
                <div class="min-w-0 xl:col-span-3">
                    <p class="{{ $label }}">Contoh hasil</p>

                    <div class="mt-2 overflow-hidden rounded-xl border border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-950/40">
                        @if ($keadaan === 'siap')
                            <iframe src="{{ route('admin.template.pratinjau', $tampil) }}#toolbar=0&navpanes=0"
                                    title="Contoh hasil {{ $s['info']['judul'] }}" class="h-[32rem] w-full"></iframe>

                        @elseif ($keadaan === 'menunggu')
                            <div data-menunggu class="flex min-h-[18rem] flex-col items-center justify-center gap-3 px-6 py-8 text-center">
                                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-blue-600 dark:bg-cyan-500/10 dark:text-cyan-300">
                                    <svg class="h-6 w-6 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3" class="opacity-20"/>
                                        <path d="M21 12a9 9 0 00-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                    </svg>
                                </span>

                                <div>
                                    <p class="text-base font-semibold text-slate-800 dark:text-slate-100">Contoh hasil sedang dibuat</p>
                                    <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500 dark:text-slate-400">
                                        Biasanya selesai dalam beberapa detik. Halaman diperbarui otomatis.
                                    </p>
                                </div>

                                @if ($macet)
                                    <div class="mt-2 max-w-md rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-left dark:border-amber-500/25 dark:bg-amber-500/10">
                                        <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">Belum selesai juga?</p>
                                        <p class="mt-0.5 text-sm text-amber-800/90 dark:text-amber-200/90">
                                            Proses antrean (<code class="font-mono text-xs">php artisan queue:work</code>) mungkin belum berjalan.
                                            Anda bisa membuat contohnya sekarang tanpa antrean.
                                        </p>

                                        @if ($bisaKelola)
                                            <form method="POST" action="{{ route('admin.template.pratinjau.sekarang', $tampil) }}" class="mt-3">
                                                @csrf
                                                <button type="submit" class="{{ $btn }} {{ $warna['utama'] }}">Buat Sekarang</button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </div>

                        @elseif ($keadaan === 'gagal')
                            <div class="flex min-h-[18rem] flex-col items-center justify-center gap-3 px-6 py-8 text-center">
                                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300">
                                    <x-ikon name="alert" class="h-6 w-6" />
                                </span>

                                <div>
                                    <p class="text-base font-semibold text-slate-800 dark:text-slate-100">Contoh PDF tidak dapat dibuat</p>
                                    <p class="mx-auto mt-1 max-w-md text-sm text-slate-500 dark:text-slate-400">
                                        {{ $tampil->pratinjau_pesan ?: 'Terjadi kesalahan saat mengubah dokumen menjadi PDF.' }}
                                    </p>
                                    <p class="mx-auto mt-2 max-w-md text-xs text-slate-500 dark:text-slate-400">
                                        Pastikan LibreOffice terpasang dan <code class="font-mono">LIBREOFFICE_PATH</code> di .env benar.
                                        Anda tetap dapat mengunduh contoh dalam bentuk DOCX di bawah.
                                    </p>
                                </div>
                            </div>

                        @else
                            <div class="flex min-h-[18rem] flex-col items-center justify-center gap-3 px-6 py-8 text-center">
                                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                    <x-ikon name="document" class="h-6 w-6" />
                                </span>

                                <div>
                                    <p class="text-base font-semibold text-slate-800 dark:text-slate-100">Belum ada template di pengaturan</p>
                                    <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500 dark:text-slate-400">
                                        Draf saat ini memakai file cadangan sistem. Unggah template agar contoh hasilnya tampil di sini.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($tampil)
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <a href="{{ route('admin.template.contoh', $tampil) }}" class="{{ $btn }} {{ $warna['netral'] }}">Unduh Contoh (DOCX)</a>

                            @if ($bisaKelola)
                                <form method="POST" action="{{ route('admin.template.pratinjau.buat', $tampil) }}">
                                    @csrf
                                    <button type="submit" class="{{ $btn }} {{ $warna['netral'] }}">Perbarui Contoh</button>
                                </form>
                            @endif
                        </div>

                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                            Contoh memakai data fiktif dan hasilnya mendekati. Hasil akhir tetap mengikuti tampilan di Microsoft Word.
                        </p>
                    @endif
                </div>

                {{-- TEMPLATE (KODE WORD) --}}
                <div class="min-w-0 space-y-4 xl:col-span-2">
                    <p class="{{ $label }}">Template (kode Word)</p>

                    {{-- Keadaan template --}}
                    @if ($draf)
                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/25 dark:bg-amber-500/10">
                            <p class="text-sm font-semibold text-amber-900 dark:text-amber-200">Menunggu aktivasi</p>
                            <p class="mt-0.5 break-words text-sm text-amber-800/90 dark:text-amber-200/90">
                                <strong>{{ $draf->nama_asli }}</strong> belum dipakai untuk draf surat. Periksa contoh hasilnya, lalu aktifkan.
                            </p>

                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <form method="POST" action="{{ route('admin.template.aktifkan', $draf) }}">
                                    @csrf
                                    <button type="submit" class="{{ $btn }} {{ $warna['utama'] }}">Aktifkan</button>
                                </form>
                                <a href="{{ route('admin.template.unduh', $draf) }}" class="{{ $btn }} {{ $warna['biru'] }}">Unduh</a>
                                <form method="POST" action="{{ route('admin.template.hapus', $draf) }}"
                                      onsubmit="return confirm('Hapus template yang belum aktif ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="{{ $btn }} {{ $warna['bahaya'] }}">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if ($aktif)
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">Template aktif</p>
                            <p class="mt-0.5 break-words text-sm text-slate-600 dark:text-slate-300">{{ $aktif->nama_asli }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Aktif sejak {{ $aktif->diaktifkan_at?->translatedFormat('d M Y, H:i') }}</p>

                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <a href="{{ route('admin.template.unduh', $aktif) }}" class="{{ $btn }} {{ $warna['biru'] }}">Unduh Template</a>

                                <form method="POST" action="{{ route('admin.template.hapus', $aktif) }}"
                                      onsubmit="return confirm('{{ $superAdmin ? 'Hapus template bawaan? Draf akan memakai file cadangan sistem.' : 'Kembalikan ke template bawaan? Template dinas ini akan dihapus.' }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="{{ $btn }} {{ $warna['bahaya'] }}">
                                        {{ $superAdmin ? 'Hapus Template' : 'Kembalikan ke Bawaan' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (! $draf && ! $aktif)
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                            <p class="text-sm text-slate-600 dark:text-slate-300">
                                @if ($warisan)
                                    Dinas ini memakai template bawaan. Unduh sebagai dasar, ubah di Word, lalu unggah sebagai template dinas.
                                @else
                                    Belum ada template di pengaturan, jadi draf memakai file cadangan sistem. Unduh sebagai dasar, ubah, lalu unggah.
                                @endif
                            </p>

                            <div class="mt-3">
                                <a href="{{ $warisan ? route('admin.template.unduh', $warisan) : route('admin.template.sistem', $dipilih) }}"
                                   class="{{ $btn }} {{ $warna['biru'] }}">
                                    {{ $warisan ? 'Unduh Template Bawaan' : 'Unduh Template Sistem' }}
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Unggah --}}
                    <form method="POST" action="{{ route('admin.template.unggah', $dipilih) }}" enctype="multipart/form-data"
                          class="rounded-xl border border-dashed border-violet-300 bg-violet-50/50 p-4 dark:border-violet-500/30 dark:bg-violet-500/5">
                        @csrf

                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                            {{ $draf || $aktif ? 'Unggah template pengganti' : 'Unggah template baru' }}
                        </p>
                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                            Format .docx, maksimal 5 MB. Kode <code class="font-mono">${...}</code> jangan diubah.
                            @if ($draf) Mengunggah lagi akan menggantikan draf yang menunggu aktivasi. @endif
                        </p>

                        <div class="mt-3 flex flex-col gap-2 sm:flex-row sm:items-center">
                            <input type="file" name="template" accept=".docx" required
                                   class="min-w-0 flex-1 cursor-pointer rounded-lg border border-slate-300 bg-white p-1 text-sm text-slate-600
                                          dark:border-slate-600 dark:bg-slate-900 dark:text-slate-300
                                          file:mr-3 file:rounded-md file:border-0 file:bg-violet-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-violet-700
                                          dark:file:bg-violet-500/15 dark:file:text-violet-300">
                            <button type="submit" class="{{ $btn }} {{ $warna['ungu'] }}">Unggah</button>
                        </div>

                        @if ($galat->has('template'))
                            <p class="mt-3 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700 dark:border-rose-500/25 dark:bg-rose-500/10 dark:text-rose-300">
                                {{ $galat->first('template') }}
                            </p>
                        @endif
                    </form>

                    {{-- Kode yang terdeteksi --}}
                    @php $sumberKode = $draf ?? $aktif; @endphp
                    @if ($sumberKode && $sumberKode->kode)
                        <details class="rounded-xl border border-slate-200 px-4 py-3 dark:border-slate-700">
                            <summary class="cursor-pointer text-sm font-semibold text-slate-700 dark:text-slate-200">
                                {{ count($sumberKode->kode) }} kode terdeteksi
                                @if ($sumberKode->peringatan)
                                    <span class="ml-1 font-normal text-amber-700 dark:text-amber-300">· {{ count($sumberKode->peringatan) }} peringatan</span>
                                @endif
                            </summary>

                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @foreach ($sumberKode->kode as $k)
                                    <code class="rounded-md bg-slate-100 px-1.5 py-0.5 font-mono text-xs text-slate-700 dark:bg-slate-800 dark:text-slate-200">${{ '{' . $k . '}' }}</code>
                                @endforeach
                            </div>

                            @foreach ($sumberKode->peringatan ?? [] as $pesan)
                                <p class="mt-2 text-xs text-amber-700 dark:text-amber-300">{{ $pesan }}</p>
                            @endforeach
                        </details>
                    @endif
                </div>
            </div>
        </section>
    </div>

    {{-- DAFTAR KODE --}}
    <details class="{{ $kartu }} p-5 md:p-6">
        <summary class="cursor-pointer text-lg font-bold text-slate-900 dark:text-white">Daftar kode yang tersedia</summary>

        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
            Tulis kode persis seperti di bawah, dengan tanda <code class="font-mono text-xs">${</code> dan <code class="font-mono text-xs">}</code>.
            Bila Word memecah kode (misalnya karena pemeriksa ejaan), ketik kodenya di Notepad, lalu tempel dengan opsi "Keep Text Only".
        </p>

        @foreach ($kode as $grup => $daftarKode)
            <p class="mt-4 {{ $label }}">{{ $grup }}</p>

            <div class="mt-1 overflow-x-auto">
                <table class="w-full min-w-[560px] text-left text-sm">
                    <tbody class="divide-y divide-slate-100 text-slate-600 dark:divide-slate-800 dark:text-slate-300">
                        @foreach ($daftarKode as $nama => [$arti, $contoh])
                            <tr>
                                <td class="w-56 py-1.5 pr-3 font-mono text-xs text-slate-800 dark:text-slate-100">${{ '{' . $nama . '}' }}</td>
                                <td class="py-1.5 pr-3">{{ $arti }}</td>
                                <td class="py-1.5 text-slate-500 dark:text-slate-400">{{ $contoh }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach

        <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">
            Template kelompok wajib memuat satu tabel dengan satu baris berisi <code class="font-mono">${no}</code>, <code class="font-mono">${nama_anggota}</code>,
            dan seterusnya. Baris itu otomatis digandakan sesuai jumlah peserta. Jangan memakai pasangan <code class="font-mono">${blok_...}</code>.
        </p>
    </details>

@endsection

@push('scripts')
<script>
    // Muat ulang otomatis selama contoh hasil dibuat (maksimal sekitar 1 menit).
    if (document.querySelector('[data-menunggu]')) {
        const n = Number(sessionStorage.getItem('muatUlangTemplate') || 0);

        if (n < 12) {
            sessionStorage.setItem('muatUlangTemplate', n + 1);
            setTimeout(() => location.reload(), 5000);
        }
    } else {
        sessionStorage.removeItem('muatUlangTemplate');
    }
</script>
@endpush