{{--
    <x-user.sertifikat :pendaftaran="$pendaftaran" />
    diterbitkan -> tombol unduh; diproses -> info; magang masih berjalan -> keterangan kapan tersedia.
--}}
@props(['pendaftaran'])

@if ($pendaftaran->statusSertifikat() === 'diterbitkan')
    <div class="mt-5 p-4 rounded-xl border border-emerald-200 dark:border-emerald-500/30 bg-emerald-50 dark:bg-emerald-500/10">
        <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">🎓 Sertifikat telah diterbitkan</p>
        <div class="mt-3 flex flex-wrap gap-2">
            <x-button :href="route('user.pendaftaran.sertifikat', ['pendaftaran' => $pendaftaran->id, 'unduh' => 1])" variant="success">
                Unduh Sertifikat (PDF)
            </x-button>
            <x-button :href="route('user.pendaftaran.sertifikat', $pendaftaran->id)" variant="secondary" target="_blank">
                Lihat
            </x-button>
        </div>
    </div>
@elseif ($pendaftaran->statusSertifikat() === 'diproses')
    <div class="mt-5 p-4 rounded-xl border border-amber-200 dark:border-amber-500/30 bg-amber-50 dark:bg-amber-500/10 flex items-start gap-3">
        <span class="text-lg leading-none">⏳</span>
        <div>
            <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">Sertifikat sedang diproses</p>
            <p class="text-xs text-amber-700 dark:text-amber-300/90 mt-0.5 leading-relaxed">
                Magangmu sudah selesai. Dinas sedang menyiapkan dan menandatangani sertifikat; tombol unduh akan muncul di sini setelah diterbitkan, dan salinannya dikirim ke emailmu.
            </p>
        </div>
    </div>
@elseif ($pendaftaran->status === 'diterima')
    <div class="mt-3 p-4 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-800/60 flex items-start gap-3">
        <span class="text-lg leading-none">🎓</span>
        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
            Sertifikat akan tersedia setelah masa magang selesai
            @if ($pendaftaran->tanggal_selesai)
                ({{ \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->translatedFormat('d M Y') }}).
            @endif
        </p>
    </div>
@endif