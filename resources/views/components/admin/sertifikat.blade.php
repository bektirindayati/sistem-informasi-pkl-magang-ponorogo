{{-- <x-admin.sertifikat :pendaftaran="$p" /> : sertifikat per peserta (ketua + anggota), satu PDF untuk tiap orang --}}
@props(['pendaftaran'])

@php
    $p        = $pendaftaran;
    $aktif    = in_array($p->status, ['diterima', 'selesai'], true);
    $terbit   = $p->sertifikatTerbit();
    $kelompok = $p->tipe === 'kelompok';
    $zona     = config('surat.zona_waktu', config('app.timezone'));

    $peserta = $p->pesertaSertifikat();
    $punya   = $peserta->filter(fn ($x) => $x['file'])->count();
    $kurang  = $punya > 0 && $punya < $peserta->count();

    $btn    = 'relative inline-flex items-center justify-center whitespace-nowrap px-3.5 py-1.5 rounded-lg border text-sm font-semibold transition';
    $kecil  = 'relative inline-flex items-center justify-center whitespace-nowrap px-2.5 py-1 rounded-md border text-xs font-semibold transition';
    $warna  = [
        'draf'   => 'border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300 dark:hover:bg-blue-500/20',
        'lihat'  => 'border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700',
        'unggah' => 'border-violet-200 bg-violet-50 text-violet-700 hover:bg-violet-100 dark:border-violet-500/30 dark:bg-violet-500/10 dark:text-violet-300 dark:hover:bg-violet-500/20',
        'simpan' => 'border-transparent bg-emerald-600 text-white hover:bg-emerald-700',
        'email'  => 'border-cyan-200 bg-cyan-50 text-cyan-700 hover:bg-cyan-100 dark:border-cyan-500/30 dark:bg-cyan-500/10 dark:text-cyan-300 dark:hover:bg-cyan-500/20',
    ];
    $input = 'w-[8.5rem] px-3 py-1.5 rounded-lg border border-slate-300 dark:border-slate-600 text-sm font-semibold text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 dark:focus:ring-cyan-500/20 outline-none transition';

    $aksen = $terbit ? 'border-l-emerald-500' : ($aktif ? 'border-l-amber-500' : 'border-l-slate-300 dark:border-l-slate-600');
    $badge = $terbit
        ? ['Diterbitkan', 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/15 dark:text-emerald-300']
        : ($aktif
            ? ['Diproses', 'bg-amber-100 text-amber-800 dark:bg-amber-500/15 dark:text-amber-300']
            : ['Belum tersedia', 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400']);

    $dipilih = old('status_sertifikat', $terbit ? 'diterbitkan' : 'diproses');
@endphp

@if ($aktif)
    <div class="border-l-4 {{ $aksen }} pl-4 py-3">
        <form action="{{ route('admin.sertifikat.status', $p->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3"
              onsubmit="return this.elements['status_sertifikat'].value === 'diproses' || confirm('Terbitkan sertifikat? Pendaftar langsung bisa mengunduhnya.')">
            @csrf

            <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                <div class="min-w-0 flex-1 basis-56">
                    <p class="text-base font-semibold text-slate-800 dark:text-slate-100">Sertifikat Magang</p>
                    <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $badge[1] }}">{{ $badge[0] }}</span>
                        @if ($terbit) {{ $p->sertifikat_terbit_at?->timezone($zona)->format('d/m/Y H:i') }} @endif
                    </p>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-2">
                    <a href="{{ route('admin.sertifikat.draf', $p->id) }}"
                       title="{{ $kelompok ? 'Unduh draf ZIP (satu DOCX per orang)' : 'Unduh draf DOCX' }}"
                       class="{{ $btn }} {{ $warna['draf'] }}">Draf</a>

                    <select name="status_sertifikat" class="{{ $input }}">
                        <option value="diproses" @selected($dipilih === 'diproses')>Diproses</option>
                        <option value="diterbitkan" @selected($dipilih === 'diterbitkan')>Diterbitkan</option>
                    </select>

                    <button type="submit" class="{{ $btn }} {{ $warna['simpan'] }}">Simpan</button>

                    @if ($terbit)
                        <button type="submit" form="email-sertifikat" title="Kirim email berisi sertifikat (PDF terlampir) ke pendaftar"
                                onclick="return confirm('Kirim email Sertifikat Magang ke pendaftar?')"
                                class="{{ $btn }} {{ $warna['email'] }}">Kirim Email</button>
                    @else
                        <span title="Terbitkan dokumen terlebih dahulu"
                              class="{{ $btn }} border-slate-200 bg-slate-50 text-slate-400 opacity-60 cursor-not-allowed dark:border-slate-700 dark:bg-slate-800/60 dark:text-slate-500">Kirim Email</span>
                    @endif
                </div>
            </div>

            {{-- Satu baris per peserta: PDF-nya menempel ke nama pemiliknya --}}
            <div class="rounded-xl border border-slate-200 dark:border-slate-700 divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($peserta as $x)
                    <div data-baris class="flex flex-wrap items-center gap-x-3 gap-y-1.5 px-3 py-2.5">
                        <span class="w-5 text-sm text-slate-400">{{ $loop->iteration }}</span>

                        <div class="min-w-0 flex-1 basis-48">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">
                                {{ $x['nama'] }}
                                @if ($x['peran'])<span class="ml-1 text-xs font-medium text-slate-500 dark:text-slate-400">{{ $x['peran'] }}</span>@endif
                            </p>
                            <p class="text-xs">
                                @if ($x['file'])
                                    <span class="text-emerald-700 dark:text-emerald-300">Tersimpan: {{ $x['file']->nama }}</span>
                                @else
                                    <span class="text-amber-700 dark:text-amber-300">Belum diunggah</span>
                                @endif
                                <span data-nama class="hidden text-violet-700 dark:text-violet-300"></span>
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            @if ($x['file'])
                                <a href="{{ route('admin.sertifikat.show', ['id' => $p->id, 'berkas' => $x['file']->id]) }}" target="_blank" rel="noopener"
                                   class="{{ $kecil }} {{ $warna['lihat'] }}">Lihat</a>

                                <label class="inline-flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400" title="Centang lalu klik Simpan untuk menghapus">
                                    <input type="checkbox" name="hapus[]" value="{{ $x['kunci'] }}" class="rounded border-slate-300 text-rose-600">
                                    Hapus
                                </label>
                            @endif

                            <button type="button" data-pratinjau class="hidden {{ $kecil }} {{ $warna['lihat'] }}">Pratinjau</button>

                            <label class="{{ $kecil }} {{ $warna['unggah'] }} cursor-pointer">
                                {{ $x['file'] ? 'Ganti' : 'Unggah' }}
                                <input type="file" name="sertifikat[{{ $x['kunci'] }}]" accept="application/pdf" class="sr-only" data-sertifikat>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <p class="text-sm {{ $kurang ? 'text-amber-700 dark:text-amber-300' : 'text-slate-500 dark:text-slate-400' }}">
                {{ $punya }} dari {{ $peserta->count() }} peserta sudah memiliki sertifikat.
                @if ($kelompok)
                    Draf berupa ZIP berisi {{ $peserta->count() }} file DOCX (satu per orang). Ekspor tiap sertifikat yang sudah ditandatangani menjadi PDF, lalu unggah pada baris nama yang sesuai.
                @endif
            </p>

            @error('status_sertifikat')<p class="text-sm font-medium text-rose-600">{{ $message }}</p>@enderror
            @if ($errors->first('sertifikat') || $errors->first('sertifikat.*'))
                <p class="text-sm font-medium text-rose-600">{{ $errors->first('sertifikat') ?: $errors->first('sertifikat.*') }}</p>
            @endif
        </form>

        <form id="email-sertifikat" method="POST" action="{{ route('admin.sertifikat.email', $p->id) }}" class="hidden">@csrf</form>
    </div>
@else
    <div class="border-l-4 {{ $aksen }} pl-4 py-3 flex flex-wrap items-center justify-between gap-x-4 gap-y-1">
        <div>
            <p class="text-base font-semibold text-slate-500 dark:text-slate-400">Sertifikat Magang</p>
            <p class="mt-0.5"><span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $badge[1] }}">{{ $badge[0] }}</span></p>
        </div>
        <p class="text-sm text-slate-500 dark:text-slate-400">Tersedia setelah pendaftaran diterima</p>
    </div>
@endif

@once
    <script>
        // Nama file yang dipilih tampil di barisnya; Pratinjau membuka PDF itu sebelum disimpan.
        document.addEventListener('change', e => {
            const input = e.target.closest('input[type="file"][data-sertifikat]');
            if (!input) return;

            const baris  = input.closest('[data-baris]');
            const berkas = input.files[0];
            const nama   = baris.querySelector('[data-nama]');

            nama.textContent = berkas ? ' · Dipilih: ' + berkas.name : '';
            nama.classList.toggle('hidden', !berkas);
            baris.querySelector('[data-pratinjau]').classList.toggle('hidden', !berkas);
        });

        document.addEventListener('click', e => {
            const tombol = e.target.closest('[data-pratinjau]');
            if (!tombol) return;

            const berkas = tombol.closest('[data-baris]').querySelector('input[type="file"]').files[0];
            if (!berkas) return;

            const url = URL.createObjectURL(berkas);
            window.open(url, '_blank', 'noopener');
            setTimeout(() => URL.revokeObjectURL(url), 60000);
        });
    </script>
@endonce