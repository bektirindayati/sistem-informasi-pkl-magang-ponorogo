{{--
    <x-admin.status-verifikasi :pendaftaran="$pendaftaran" />
    Status verifikasi (menunggu / revisi / diterima / ditolak / selesai). Kolom alasan muncul untuk revisi dan ditolak.
--}}
@props(['pendaftaran'])

@php
    $pilihan = [
        'pending'  => '⏳ Menunggu Verifikasi',
        'revisi'   => '✏️ Perlu Revisi',
        'diterima' => '✅ Diterima',
        'ditolak'  => '❌ Ditolak',
    ];

// "Selesai" dapat dipilih kapan saja setelah pendaftaran diterima.
$bisaSelesai = in_array($pendaftaran->status, ['diterima', 'selesai'], true);

if ($bisaSelesai) {
    $pilihan['selesai'] = '✓ Magang Selesai';
}

    $menungguSelesai = $pendaftaran->status === 'diterima' && ! $bisaSelesai;
    $bisaUbah = array_key_exists($pendaftaran->status, $pilihan);
    $adaDokumenTerbit = $pendaftaran->suratTerbit() || $pendaftaran->sertifikatTerbit();

    $input = 'w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 text-sm bg-slate-50 dark:bg-slate-900 focus:bg-white focus:border-cyan-400 focus:ring-2 focus:ring-cyan-100 outline-none transition';
    $label = 'block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5';
@endphp

<div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm p-6">

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Status Verifikasi</h3>
            <p class="text-xs text-slate-500 mt-0.5">Keputusan atas pendaftaran ini. Pendaftar langsung melihat perubahannya.</p>
        </div>
        <x-status-badge :status="$pendaftaran->status" />
    </div>

    @if ($bisaUbah)
        <form action="{{ route('admin.verifikasi.update', $pendaftaran->id) }}" method="POST" class="mt-5 space-y-4"
              @if ($adaDokumenTerbit)
                  onsubmit="return this.status.value === '{{ $pendaftaran->status }}' || confirm('Mengubah status dapat menarik surat atau sertifikat yang sudah diterbitkan dari pendaftar. Lanjutkan?')"
              @endif>
            @csrf
            @method('PUT')

            <label class="block">
                <span class="{{ $label }}">Ubah Status</span>
                <select name="status" id="statusVerifikasi" class="{{ $input }} font-semibold">
                    @foreach ($pilihan as $nilai => $teks)
                <option value="{{ $nilai }}" @selected(old('status', $pendaftaran->status) === $nilai)>
                    {{ $teks }}
                </option>
            @endforeach
                </select>
                @error('status')<p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
                @if ($pendaftaran->status === 'diterima')
                <p class="mt-1 text-[11px] text-slate-400">
                    Pilih "Magang Selesai" ketika pendaftar telah menyelesaikan magang
                    dan mengajukan penerbitan sertifikat.
                </p>
            @endif
            </label>

            <label class="block hidden" id="alasanRevisi">
                <span class="{{ $label }}">Alasan revisi <span class="text-rose-500">*</span></span>
                <textarea name="alasan_revisi" rows="3" class="{{ $input }}" placeholder="Jelaskan bagian yang perlu diperbaiki pendaftar">{{ old('alasan_revisi', $pendaftaran->alasan_revisi) }}</textarea>
                @error('alasan_revisi')<p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
            </label>

            <label class="block hidden" id="alasanTolak">
                <span class="{{ $label }}">Alasan penolakan <span class="text-rose-500">*</span></span>
                <textarea name="alasan_penolakan" rows="3" class="{{ $input }}" placeholder="Jelaskan alasan pendaftaran ditolak">{{ old('alasan_penolakan', $pendaftaran->alasan_penolakan) }}</textarea>
                @error('alasan_penolakan')<p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>@enderror
            </label>

            <button type="submit" class="px-6 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-bold shadow-sm transition">
                Simpan Status
            </button>
        </form>

        <script>
            (() => {
                const pilih = document.getElementById('statusVerifikasi');
                const alasan = {
                    revisi: document.getElementById('alasanRevisi'),
                    ditolak: document.getElementById('alasanTolak'),
                };
                const tampil = () => Object.entries(alasan)
                    .forEach(([nilai, el]) => el.classList.toggle('hidden', pilih.value !== nilai));

                pilih.addEventListener('change', tampil);
                tampil();
            })();
        </script>
    @else
        <p class="mt-4 text-sm text-slate-500">Status pendaftaran ini tidak dapat diubah dari halaman ini.</p>
    @endif
</div>