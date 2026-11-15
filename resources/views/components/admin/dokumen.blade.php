{{-- <x-admin.dokumen jenis="balasan|selesai|sertifikat" :pendaftaran="$p" /> --}}
@props(['pendaftaran', 'jenis'])

@php
    $p        = $pendaftaran;
    $kelompok = $p->tipe === 'kelompok';
    $zona     = config('surat.zona_waktu', config('app.timezone'));

    // Ketiga dokumen terbuka sejak pendaftaran diterima.
    $aktif = in_array($p->status, ['diterima', 'selesai'], true);

    $c = [
        'balasan' => [
            'judul' => 'Surat Balasan (Diterima)', 'rute' => 'admin.surat',
            'status' => 'status_surat', 'pdf' => 'surat_balasan',
            'file' => 'file_surat_balasan', 'waktu' => 'surat_balasan_terbit_at', 'terbit' => $p->suratTerbit(),
            'konfirmasi' => 'Terbitkan surat balasan? Pendaftar langsung bisa mengunduhnya.',
        ],
        'selesai' => [
            'judul' => 'Surat Selesai Magang', 'rute' => 'admin.surat-selesai',
            'status' => 'status_surat_selesai', 'pdf' => 'surat_selesai',
            'file' => 'file_surat_selesai', 'waktu' => 'surat_selesai_terbit_at', 'terbit' => $p->suratSelesaiTerbit(),
            'konfirmasi' => 'Terbitkan surat selesai magang? Pendaftar langsung bisa mengunduhnya.',
        ],
        'sertifikat' => [
            'judul' => 'Sertifikat Magang', 'rute' => 'admin.sertifikat',
            'status' => 'status_sertifikat', 'pdf' => 'sertifikat',
            'file' => 'file_sertifikat', 'waktu' => 'sertifikat_terbit_at', 'terbit' => $p->sertifikatTerbit(),
            'konfirmasi' => 'Terbitkan sertifikat? Pendaftar langsung bisa mengunduhnya.',
        ],
    ][$jenis];

    $zip    = $jenis === 'sertifikat' && $kelompok;
    $terbit = $c['terbit'];
    $adaPdf = filled($p->{$c['file']});

    $btn   = 'relative inline-flex h-9 items-center justify-center whitespace-nowrap px-3.5 rounded-lg border text-xs font-bold transition';
    $warna = [
    'draf'   => 'border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300 dark:hover:bg-blue-500/20',
    'lihat'  => 'border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700',
    'unggah' => 'border-violet-200 bg-violet-50 text-violet-700 hover:bg-violet-100 focus-within:ring-2 focus-within:ring-violet-400/40 dark:border-violet-500/30 dark:bg-violet-500/10 dark:text-violet-300 dark:hover:bg-violet-500/20',
    'simpan' => 'border-transparent bg-emerald-600 text-white hover:bg-emerald-700',
    'email'  => 'border-cyan-200 bg-cyan-50 text-cyan-700 hover:bg-cyan-100 dark:border-cyan-500/30 dark:bg-cyan-500/10 dark:text-cyan-300 dark:hover:bg-cyan-500/20',
];
    $input = 'h-9 w-[8.5rem] appearance-none pl-3 pr-8 rounded-lg border border-slate-300 dark:border-slate-600 text-xs font-bold text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 dark:focus:ring-cyan-500/20 outline-none transition';

    $aksen = $terbit ? 'border-l-emerald-500' : ($aktif ? 'border-l-amber-500' : 'border-l-slate-300 dark:border-l-slate-600');

    $badge = $terbit
        ? ['Diterbitkan', 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-300']
        : ($aktif
            ? ['Diproses', 'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-300']
            : ['Belum tersedia', 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400']);

    $dipilih  = old($c['status'], $terbit ? 'diterbitkan' : 'diproses');
    $urlLihat = $adaPdf ? route($c['rute'] . '.show', $p->id) : '';

    // Peringatan data belum lengkap (hanya surat balasan; berguna untuk data lama).
    $peringatan = collect();
    if ($jenis === 'balasan' && $aktif) {
        $dinas = $p->dinas;
        $peringatan = collect([
            'kop dinas: alamat' => $dinas?->alamat, 'kop dinas: telepon' => $dinas?->telepon,
            'kop dinas: website' => $dinas?->website, 'kop dinas: email' => $dinas?->email,
            'nomor surat pengantar' => $p->nomor_surat_pengantar, 'tanggal surat pengantar' => $p->tanggal_surat_pengantar,
            'pengirim surat' => $p->jabatan_penerima, 'kota kampus / sekolah' => $p->kota_tujuan,
        ])->filter(fn ($v) => blank($v))->keys();
    }
@endphp

@if ($aktif)
    <form action="{{ route($c['rute'] . '.status', $p->id) }}" method="POST" enctype="multipart/form-data"
          class="border-l-4 {{ $aksen }} pl-4 py-3 flex flex-wrap items-center gap-x-4 gap-y-2"
          data-konfirmasi="{{ $c['konfirmasi'] }}"
          onsubmit="const s = this.elements['{{ $c['status'] }}'].value; return s === 'diproses' || confirm(this.dataset.konfirmasi)">
        @csrf

        <div class="min-w-0 flex-1 basis-56">
            <p class="text-base font-semibold text-slate-800 dark:text-slate-100">{{ $c['judul'] }}</p>
            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $badge[1] }}">{{ $badge[0] }}</span>
                @if ($terbit)
                    {{ $p->{$c['waktu']}?->timezone($zona)->format('d/m/Y H:i') }}
                @endif
            </p>
            <p data-nama class="hidden mt-0.5 text-sm text-violet-700 dark:text-violet-300 truncate"></p>
        </div>

        <div class="flex flex-wrap items-center justify-end gap-2">
            <a href="{{ route($c['rute'] . '.draf', $p->id) }}"
               title="{{ $zip ? 'Unduh draf ZIP (satu DOCX per orang)' : 'Unduh draf DOCX' }}"
               class="{{ $btn }} {{ $warna['draf'] }}">Draf</a>

            {{-- Memilih file TIDAK mengubah status; status baru berubah setelah Simpan. --}}
            <label class="{{ $btn }} {{ $warna['unggah'] }} cursor-pointer"
                   title="Pilih PDF resmi yang sudah ditandatangani">
                Unggah
                <input type="file" name="{{ $c['pdf'] }}" accept="application/pdf" class="sr-only" data-pdf>
            </label>

            {{-- Pratinjau berkas yang dipilih, atau PDF tersimpan bila belum ada pilihan. --}}
            <button type="button" data-lihat="{{ $urlLihat }}"
                    title="Pratinjau berkas yang dipilih, atau PDF yang sudah tersimpan"
                    class="{{ $btn }} {{ $warna['lihat'] }} {{ $adaPdf ? '' : 'opacity-60' }}">Lihat</button>

            <div class="relative">
                <select name="{{ $c['status'] }}" class="{{ $input }}">
                    <option value="diproses" @selected($dipilih === 'diproses')>Diproses</option>
                    <option value="diterbitkan" @selected($dipilih === 'diterbitkan')>Diterbitkan</option>
                </select>

                <svg class="pointer-events-none absolute right-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"
                     stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 8 4 4 4-4"/>
                </svg>
            </div>

            <button type="submit" class="{{ $btn }} {{ $warna['simpan'] }}">Simpan</button>

            {{-- Tombol kirim email terpisah dari penyimpanan status. --}}
            @if ($terbit)
                <button type="submit" form="email-{{ $jenis }}"
                        title="Kirim email berisi dokumen (PDF terlampir) ke pendaftar"
                        onclick="return confirm('Kirim email {{ $c['judul'] }} ke pendaftar?')"
                        class="{{ $btn }} {{ $warna['email'] }}">Kirim Email</button>
            @else
                <span title="Terbitkan dokumen terlebih dahulu"
                      class="{{ $btn }} border-slate-200 bg-slate-50 text-slate-400 opacity-60 cursor-not-allowed
                             dark:border-slate-700 dark:bg-slate-800/60 dark:text-slate-500">Kirim Email</span>
            @endif
        </div>

        @if ($zip)
            <p class="basis-full text-sm text-slate-500 dark:text-slate-400">
                Draf berupa ZIP berisi {{ $p->anggotas->count() + 1 }} file DOCX (satu per orang). Gabungkan semuanya menjadi satu PDF sebelum diunggah.
            </p>
        @endif

        @if ($peringatan->isNotEmpty())
            <p class="basis-full rounded-lg bg-amber-50 px-3 py-2 text-xs font-medium text-amber-800 dark:bg-amber-500/10 dark:text-amber-300">
                Data belum lengkap: {{ $peringatan->implode(', ') }}.
            </p>
        @endif

        @error($c['status'])
            <p class="basis-full text-sm font-medium text-rose-600">{{ $message }}</p>
        @enderror

        @error($c['pdf'])
            <p class="basis-full text-sm font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </form>

    {{-- Form khusus untuk mengirim email tanpa mengubah status atau mengunggah file. --}}
    <form id="email-{{ $jenis }}"
          method="POST"
          action="{{ route($c['rute'] . '.email', $p->id) }}"
          class="hidden">
        @csrf
    </form>
@else
    <div class="border-l-4 {{ $aksen }} pl-4 py-3 flex flex-wrap items-center justify-between gap-x-4 gap-y-1">
        <div>

            <p class="text-base font-semibold text-slate-500 dark:text-slate-400">{{ $c['judul'] }}</p>
            <p class="mt-0.5"><span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $badge[1] }}">{{ $badge[0] }}</span></p>
        </div>
        <p class="text-sm text-slate-500 dark:text-slate-400">Tersedia setelah pendaftaran diterima</p>
    </div>
@endif

@once
    <script>
        // Nama file tampil di bawah judul dokumen; tombol Lihat aktif begitu ada pilihan.
        document.addEventListener('change', e => {
            const input = e.target.closest('input[type="file"][data-pdf]');
            if (!input) return;

            const berkas = input.files[0];
            const nama   = input.form.querySelector('[data-nama]');
            const lihat  = input.form.querySelector('[data-lihat]');

            nama.textContent = berkas ? 'Berkas dipilih: ' + berkas.name : '';
            nama.classList.toggle('hidden', !berkas);
            lihat.classList.toggle('opacity-60', !berkas && !lihat.dataset.lihat);
        });

        document.addEventListener('click', e => {
            const tombol = e.target.closest('[data-lihat]');
            if (!tombol) return;

            const berkas = tombol.closest('form').querySelector('input[type="file"]').files[0];
            const url = berkas ? URL.createObjectURL(berkas) : tombol.dataset.lihat;

            if (!url) {
                alert('Belum ada PDF. Pilih berkas lewat tombol "Unggah" terlebih dahulu.');
                return;
            }

            window.open(url, '_blank', 'noopener');
            if (berkas) setTimeout(() => URL.revokeObjectURL(url), 60000);
        });
    </script>
@endonce