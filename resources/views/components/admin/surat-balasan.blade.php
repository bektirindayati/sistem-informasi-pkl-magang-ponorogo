{{--
    <x-admin.surat-balasan :pendaftaran="$pendaftaran" />
    Status surat (diproses / diterbitkan) + detail surat: unduh draf DOCX, unggah PDF resmi.
    Hanya tampil untuk pendaftaran berstatus diterima/selesai.
--}}
@props(['pendaftaran'])

@if (in_array($pendaftaran->status, ['diterima', 'selesai'], true))
    @php
        $terbit  = $pendaftaran->suratTerbit();
        $adaPdf  = filled($pendaftaran->file_surat_balasan);
        $zona    = config('surat.zona_waktu', config('app.timezone'));
        $input   = 'w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 text-sm bg-slate-50 dark:bg-slate-900 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none transition';
        $label   = 'block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5';
        $dipilih = old('status_surat', $terbit ? 'diterbitkan' : 'diproses');

        // Data surat pengantar yang belum lengkap (dipakai mengisi draf).
        $kurang = collect([
            'Nomor surat' => $pendaftaran->nomor_surat_pengantar,
            'Tanggal surat' => $pendaftaran->tanggal_surat_pengantar,
            'Jabatan penerima' => $pendaftaran->jabatan_penerima,
            'Kota kampus / sekolah' => $pendaftaran->kota_tujuan,
        ])->filter(fn ($nilai) => blank($nilai))->keys();

        // Data kop dinas yang belum lengkap.
        $dinas = $pendaftaran->dinas;
        $kopKurang = collect([
            'Alamat'  => $dinas?->alamat,
            'Telepon' => $dinas?->telepon,
            'Website' => $dinas?->website,
            'Email'   => $dinas?->email,
        ])->filter(fn ($nilai) => blank($nilai))->keys();
    @endphp

    <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-6 space-y-5">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Surat Balasan</h3>
                <p class="text-xs text-slate-500 mt-0.5">Draf Word terisi otomatis; nomor surat dan tanda tangan dilengkapi dinas.</p>
            </div>
            <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wide {{ $terbit ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                {{ $terbit ? 'Diterbitkan' : 'Sedang diproses' }}
            </span>
        </div>

        <form action="{{ route('admin.surat.status', $pendaftaran->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5"
              onsubmit="return this.status_surat.value !== 'diterbitkan' || confirm('Terbitkan surat balasan? Pendaftar langsung bisa mengunduhnya.')">
            @csrf

            {{-- Status surat --}}
            <label class="block">
                <span class="{{ $label }}">Status Surat</span>
                <select name="status_surat" class="{{ $input }} font-semibold">
                    <option value="diproses" @selected($dipilih === 'diproses')>⏳ Surat sedang diproses</option>
                    <option value="diterbitkan" @selected($dipilih === 'diterbitkan')>✅ Surat sudah diterbitkan</option>
                </select>
                @error('status_surat')<p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
            </label>

            @if ($kopKurang->isNotEmpty())
                <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800">
                    <strong>Data kop dinas belum lengkap:</strong> {{ $kopKurang->implode(', ') }}.
                    Lengkapi di menu Kelola Dinas agar kop pada draf surat terisi.
                </div>
            @endif

           {{-- Detail surat --}}
<div class="rounded-2xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/50 p-4 space-y-4">
    <p class="text-xs font-extrabold text-slate-700 dark:text-slate-200">Detail Surat</p>

    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('admin.surat.draf', $pendaftaran->id) }}"
           class="px-4 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold transition">
            Unduh Draf Surat (DOCX)
        </a>

        @if ($adaPdf)
            <a href="{{ route('admin.surat.show', $pendaftaran->id) }}"
               target="_blank"
               rel="noopener"
               class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-slate-100 text-xs font-bold transition">
                Lihat Surat Penerimaan Magang
            </a>
        @endif
    </div>
</div>

                @if ($adaPdf)
                    <p class="text-[11px] font-semibold {{ $terbit ? 'text-emerald-600' : 'text-amber-600' }}">
                        {{ $terbit
                            ? 'PDF ini sudah bisa diunduh pendaftar.'
                            : 'PDF ini sudah diunggah tetapi belum terlihat oleh pendaftar. Ubah status surat ke "sudah diterbitkan", lalu simpan, untuk menerbitkannya.' }}
                    </p>
                @endif

                <p class="text-[11px] text-slate-400 leading-relaxed">
                    Alur: unduh draf, dinas melengkapi nomor dan tanda tangan, simpan sebagai PDF, lalu unggah di bawah.
                    Memilih file otomatis mengubah status menjadi "sudah diterbitkan".
                </p>

                <label class="block">
                    <span class="{{ $label }}">{{ $adaPdf ? 'Ganti PDF surat resmi' : 'Unggah PDF surat resmi' }} (maks. 5 MB)</span>
                    <input type="file" name="surat_balasan" accept="application/pdf"
                           onchange="if (this.files.length) this.form.status_surat.value = 'diterbitkan'"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-cyan-50 file:text-cyan-700 border border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-800">
                    @error('surat_balasan')<p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
                </label>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-sm transition">
                    Simpan Status Surat
                </button>
                @if ($terbit)
                    <span class="text-xs text-slate-500">
                        Diterbitkan {{ $pendaftaran->surat_balasan_terbit_at?->timezone($zona)->format('d/m/Y H:i') }}
                    </span>
                @endif
            </div>
        </form>

        {{-- Data surat pengantar yang dipakai mengisi draf --}}
        <details @if ($kurang->isNotEmpty() || $errors->hasAny(['nomor_surat_pengantar', 'tanggal_surat_pengantar', 'jabatan_penerima', 'kota_tujuan'])) open @endif
                 class="rounded-2xl border border-slate-200 dark:border-slate-600 px-4 py-3">
            <summary class="cursor-pointer text-xs font-bold text-slate-600 dark:text-slate-300">
                Data surat pengantar (dipakai mengisi draf)
                @if ($kurang->isNotEmpty())
                    <span class="ml-2 text-amber-700">belum lengkap: {{ $kurang->implode(', ') }}</span>
                @endif
            </summary>

            <form action="{{ route('admin.surat-pengantar.update', $pendaftaran->id) }}" method="POST"
                  class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                @csrf
                @method('PUT')

                <label class="block">
                    <span class="{{ $label }}">Nomor surat</span>
                    <input type="text" name="nomor_surat_pengantar" class="{{ $input }}"
                           value="{{ old('nomor_surat_pengantar', $pendaftaran->nomor_surat_pengantar) }}">
                    @error('nomor_surat_pengantar')<p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
                </label>
                <label class="block">
                    <span class="{{ $label }}">Tanggal surat</span>
                    <input type="date" name="tanggal_surat_pengantar" class="{{ $input }}"
                           value="{{ old('tanggal_surat_pengantar', $pendaftaran->tanggal_surat_pengantar ? \Carbon\Carbon::parse($pendaftaran->tanggal_surat_pengantar)->format('Y-m-d') : '') }}">
                    @error('tanggal_surat_pengantar')<p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
                </label>
                <label class="block">
                    <span class="{{ $label }}">Jabatan penerima</span>
                    <input type="text" name="jabatan_penerima" class="{{ $input }}"
                           value="{{ old('jabatan_penerima', $pendaftaran->jabatan_penerima) }}">
                    @error('jabatan_penerima')<p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
                </label>
                <label class="block">
                    <span class="{{ $label }}">Kota kampus / sekolah</span>
                    <input type="text" name="kota_tujuan" class="{{ $input }}"
                           value="{{ old('kota_tujuan', $pendaftaran->kota_tujuan) }}">
                    @error('kota_tujuan')<p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
                </label>

                <div class="sm:col-span-2">
                    <button type="submit" class="px-5 py-2 rounded-xl bg-slate-700 hover:bg-slate-800 text-white text-xs font-bold transition">
                        Simpan Data Surat Pengantar
                    </button>
                </div>
            </form>
        </details>
    </div>
@endif