
{{--
    <x-user.surat-balasan :pendaftaran="$pendaftaran" />
    diterbitkan -> tombol unduh PDF; diproses -> info "sedang diproses".
--}}
@props(['pendaftaran'])

@if ($pendaftaran->statusSurat() === 'diterbitkan')
    <div class="mt-5 p-4 rounded-xl
                border border-emerald-200 dark:border-emerald-500/30
                bg-emerald-50 dark:bg-emerald-500/10">

        <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">
            Surat balasan telah diterbitkan
        </p>

        <div class="mt-3 flex flex-wrap gap-2">
            <x-button
                :href="route('user.pendaftaran.surat', ['pendaftaran' => $pendaftaran->id, 'unduh' => 1])"
                variant="success">
                Unduh Surat Balasan (PDF)
            </x-button>

            <x-button
                :href="route('user.pendaftaran.surat', $pendaftaran->id)"
                variant="secondary"
                target="_blank">
                Lihat
            </x-button>
        </div>
    </div>

@elseif ($pendaftaran->statusSurat() === 'diproses')
    <div class="mt-5 p-4 rounded-xl
                border border-amber-200 dark:border-amber-500/30
                bg-amber-50 dark:bg-amber-500/10
                flex items-start gap-3">

        <span class="text-lg leading-none shrink-0">⏳</span>

        <div>
            <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">
                Surat sedang diproses
            </p>

            <p class="text-xs text-amber-700 dark:text-amber-300/90 mt-0.5 leading-relaxed">
                Dinas sedang menyiapkan dan menandatangani surat balasan.
                Tombol unduh akan muncul di sini setelah surat diterbitkan.
            </p>
        </div>
    </div>
@endif
