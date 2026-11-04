{{--
    <x-admin.sertifikat :pendaftaran="$pendaftaran" />
    Selama magang berjalan: hanya info. Setelah ditandai Selesai: status sertifikat, unduh draf DOCX, unggah PDF.
--}}
@props(['pendaftaran'])

@if ($pendaftaran->status === 'diterima')
    <div class="rounded-3xl border border-dashed border-slate-300 dark:border-slate-600 p-5 text-sm text-slate-500 dark:text-slate-400">
        <strong class="text-slate-700 dark:text-slate-200">Sertifikat</strong>

        <span class="block mt-1">
            Sertifikat belum tersedia. Ketika pendaftar telah menyelesaikan
            magang dan mengajukan penerbitan sertifikat, ubah
            <em>Status Verifikasi</em> di atas menjadi "Magang Selesai"
            untuk membuka panel penerbitan sertifikat.
        </span>
    </div>

@elseif ($pendaftaran->status === 'selesai')
    @php
        $terbit  = $pendaftaran->sertifikatTerbit();
        $adaPdf  = filled($pendaftaran->file_sertifikat);
        $zona    = config('surat.zona_waktu', config('app.timezone'));
        $input   = 'w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 text-sm bg-slate-50 dark:bg-slate-900 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none transition';
        $label   = 'block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5';
        $dipilih = old('status_sertifikat', $terbit ? 'diterbitkan' : 'diproses');
    @endphp

    <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-6 space-y-5">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Sertifikat Magang</h3>
                <p class="text-xs text-slate-500 mt-0.5">Draf Word terisi otomatis; nomor sertifikat dan tanda tangan dilengkapi dinas.</p>
            </div>
            <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide {{ $terbit ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                {{ $terbit ? 'Diterbitkan' : 'Sedang diproses' }}
            </span>
        </div>

        <form action="{{ route('admin.sertifikat.status', $pendaftaran->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5"
              onsubmit="return this.status_sertifikat.value !== 'diterbitkan' || confirm('Terbitkan sertifikat? Pendaftar langsung bisa mengunduhnya dan menerima emailnya.')">
            @csrf

            <label class="block">
                <span class="{{ $label }}">Status Sertifikat</span>
                <select name="status_sertifikat" class="{{ $input }} font-semibold">
                    <option value="diproses" @selected($dipilih === 'diproses')>⏳ Sertifikat sedang diproses</option>
                    <option value="diterbitkan" @selected($dipilih === 'diterbitkan')>✅ Sertifikat sudah diterbitkan</option>
                </select>
                @error('status_sertifikat')<p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
            </label>

            <div class="rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 p-4 space-y-4">
                <p class="text-xs font-extrabold text-slate-700 dark:text-slate-200">Detail Sertifikat</p>

                <div class="flex flex-wrap items-center gap-2">
    <a href="{{ route('admin.sertifikat.draf', $pendaftaran->id) }}"
       class="px-4 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold transition">
        Unduh Draf Sertifikat (DOCX)
    </a>

    @if ($adaPdf)
        <a href="{{ route('admin.sertifikat.show', $pendaftaran->id) }}"
           target="_blank"
           rel="noopener"
           class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-slate-100 text-xs font-bold transition">
            Lihat Sertifikat Magang
        </a>
    @endif
</div>

                @if ($adaPdf)
                    <p class="text-[11px] font-semibold {{ $terbit ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $terbit
                            ? 'PDF ini sudah bisa diunduh pendaftar.'
                            : 'PDF ini sudah diunggah tetapi belum terlihat oleh pendaftar. Ubah status ke "sudah diterbitkan", lalu simpan, untuk menerbitkannya.' }}
                    </p>
                @endif

                <p class="text-[11px] text-slate-400 leading-relaxed">
                    Alur: unduh draf, dinas melengkapi nomor dan tanda tangan, simpan sebagai PDF, lalu unggah di bawah.
                    Memilih file otomatis mengubah status menjadi "sudah diterbitkan".
                </p>

                <label class="block">
                    <span class="{{ $label }}">{{ $adaPdf ? 'Ganti PDF sertifikat resmi' : 'Unggah PDF sertifikat resmi' }} (maks. 5 MB)</span>
                    <input type="file" name="sertifikat" accept="application/pdf"
                           onchange="if (this.files.length) this.form.status_sertifikat.value = 'diterbitkan'"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-cyan-50 file:text-cyan-700 border border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-800">
                    @error('sertifikat')<p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
                </label>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-sm transition">
                    Simpan Status Sertifikat
                </button>
                @if ($terbit)
                    <span class="text-xs text-slate-500">
                        Diterbitkan {{ $pendaftaran->sertifikat_terbit_at?->timezone($zona)->format('d/m/Y H:i') }}
                    </span>
                @endif
            </div>
        </form>
    </div>
@endif