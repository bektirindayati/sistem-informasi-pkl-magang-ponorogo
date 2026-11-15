@props(['pendaftaran'])

@php
    $p = $pendaftaran;

    $pilihan = [
        'pending'  => 'Menunggu Verifikasi',
        'revisi'   => 'Perlu Revisi',
        'diterima' => 'Diterima',
        'ditolak'  => 'Ditolak',
    ];

    if (in_array($p->status, ['diterima', 'selesai'], true)) {
        $pilihan['selesai'] = 'Magang Selesai';
    }

    $bisaUbah  = array_key_exists($p->status, $pilihan);
    $adaTerbit = $p->suratTerbit() || $p->suratSelesaiTerbit() || $p->sertifikatTerbit();

    $aksen = [
        'pending' => 'border-l-amber-500', 'revisi' => 'border-l-orange-500', 'diterima' => 'border-l-emerald-500',
        'ditolak' => 'border-l-rose-500', 'selesai' => 'border-l-cyan-500',
    ][$p->status] ?? 'border-l-slate-300';

    $input = 'h-9 w-[11.5rem] px-3 rounded-lg border border-slate-300 dark:border-slate-600 text-xs font-bold text-slate-800 dark:text-slate-100 bg-white dark:bg-slate-900 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 dark:focus:ring-cyan-500/20 outline-none transition';
    $btn   = 'relative inline-flex h-9 items-center justify-center whitespace-nowrap px-3.5 rounded-lg border border-transparent text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition';
@endphp

<div class="border-l-4 {{ $aksen }} pl-4 py-3">
    @if ($bisaUbah)
        <form action="{{ route('admin.verifikasi.update', $p->id) }}" method="POST"
              class="flex flex-wrap items-center gap-x-4 gap-y-2"
              @if ($adaTerbit)
                  onsubmit="return this.status.value === '{{ $p->status }}' || confirm('Mengubah status dapat menarik surat atau sertifikat yang sudah diterbitkan dari pendaftar. Lanjutkan?')"
              @endif>
            @csrf
            @method('PUT')

            <div class="min-w-0 flex-1 basis-56">
                <p class="text-base font-semibold text-slate-800 dark:text-slate-100">Status Verifikasi</p>
                <p class="text-sm text-slate-500 dark:text-slate-400">Keputusan atas pendaftaran ini</p>
            </div>

            <div class="flex items-center justify-end gap-2">
                <select name="status" id="statusVerifikasi" class="{{ $input }}">
                    @foreach ($pilihan as $nilai => $teks)
                        <option value="{{ $nilai }}" @selected(old('status', $p->status) === $nilai)>{{ $teks }}</option>
                    @endforeach
                </select>

                <button type="submit" class="{{ $btn }}">Simpan</button>
            </div>

            @foreach (['revisi'  => ['alasanRevisi', 'alasan_revisi', 'Tulis alasan revisi (wajib)'],
                       'ditolak' => ['alasanTolak', 'alasan_penolakan', 'Tulis alasan penolakan (wajib)']] as $nilai => [$id, $nama, $contoh])
                <div class="hidden basis-full" id="{{ $id }}">
                    <textarea name="{{ $nama }}" rows="2" class="{{ $input }} w-full" placeholder="{{ $contoh }}">{{ old($nama, $p->{$nama}) }}</textarea>
                    @error($nama)<p class="mt-1 text-sm font-medium text-rose-600">{{ $message }}</p>@enderror
                </div>
            @endforeach

            @error('status')<p class="basis-full text-sm font-medium text-rose-600">{{ $message }}</p>@enderror
        </form>

        <script>
            (() => {
                const pilih  = document.getElementById('statusVerifikasi');
                const alasan = { revisi: document.getElementById('alasanRevisi'), ditolak: document.getElementById('alasanTolak') };
                const tampil = () => Object.entries(alasan)
                    .forEach(([nilai, el]) => el.classList.toggle('hidden', pilih.value !== nilai));

                pilih.addEventListener('change', tampil);
                tampil();
            })();
        </script>
    @else
        <p class="text-base text-slate-500 dark:text-slate-400">Status pendaftaran ini tidak dapat diubah dari halaman ini.</p>
    @endif
</div>