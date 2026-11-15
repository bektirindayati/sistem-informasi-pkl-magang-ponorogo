{{--
    Popup "Pendaftaran Diterima". Dipanggil dari dashboard user:
    @include('user.partials.popup-diterima')
    Memakai $pendaftaran dari halaman induk. Muncul sekali per pendaftaran di browser ini.
--}}
@php
    $suratTerbit = $pendaftaran->suratTerbit();
    $periodeMagang = ($pendaftaran->tanggal_mulai && $pendaftaran->tanggal_selesai)
        ? \Carbon\Carbon::parse($pendaftaran->tanggal_mulai)->translatedFormat('d M Y')
          . ' – '
          . \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->translatedFormat('d M Y')
        : 'Belum ditentukan';

    $barisCls = 'flex items-center gap-3 rounded-xl border p-3 text-left';
@endphp

<style>
    @keyframes pop-masuk {
        from { opacity: 0; transform: translateY(14px) scale(.97); }
        to   { opacity: 1; transform: none; }
    }
    #modalDiterima.flex .pop-card { animation: pop-masuk .35s cubic-bezier(.2, .8, .2, 1); }
    @media (prefers-reduced-motion: reduce) { #modalDiterima .pop-card { animation: none; } }
</style>

<div id="modalDiterima" role="dialog" aria-modal="true" aria-labelledby="judulDiterima"
     class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm dark:bg-slate-950/75">

    <div class="pop-card max-h-[calc(100vh-2rem)] w-full max-w-md overflow-y-auto rounded-3xl border border-slate-200 bg-white
                shadow-2xl dark:border-slate-700 dark:bg-slate-900">

        {{-- Header --}}
        <div class="border-b border-emerald-100 bg-emerald-50 px-6 py-6 text-center dark:border-emerald-500/20 dark:bg-emerald-500/10">
            <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-100
                        text-emerald-600 dark:border-emerald-400/25 dark:bg-emerald-500/15 dark:text-emerald-300">
                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span class="inline-block rounded-full border border-emerald-200 bg-emerald-100 px-3 py-1 text-xs font-extrabold uppercase
                         tracking-wide text-emerald-700 dark:border-emerald-400/25 dark:bg-emerald-500/15 dark:text-emerald-300">
                Pendaftaran Diterima
            </span>
        </div>

        {{-- Isi --}}
        <div class="px-6 py-6 text-center">
            <h2 id="judulDiterima" class="text-2xl font-extrabold leading-snug tracking-tight text-slate-900 dark:text-white">
                Selamat, kamu diterima magang!
            </h2>

            <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                Pendaftaranmu di
                <strong class="font-bold text-emerald-600 dark:text-emerald-300">{{ $pendaftaran->dinas_tujuan }}</strong>
                telah disetujui.
            </p>

            @if ($pendaftaran->tipe === 'kelompok')
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Kelompok &bull; {{ $pendaftaran->anggotas->count() + 1 }} orang
                </p>
            @endif

            <dl class="mt-5 grid gap-2.5">
                {{-- Periode --}}
                <div class="{{ $barisCls }} border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800/60">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-200 text-slate-600 dark:bg-slate-700 dark:text-slate-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">Periode magang</dt>
                        <dd class="mt-0.5 text-sm font-bold text-slate-800 dark:text-slate-100">{{ $periodeMagang }}</dd>
                    </div>
                </div>

                {{-- Surat balasan --}}
                <div class="{{ $barisCls }} {{ $suratTerbit
                        ? 'border-emerald-200 bg-emerald-50 dark:border-emerald-400/25 dark:bg-emerald-500/10'
                        : 'border-amber-200 bg-amber-50 dark:border-amber-400/25 dark:bg-amber-500/10' }}">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $suratTerbit
                            ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-300'
                            : 'bg-amber-100 text-amber-600 dark:bg-amber-500/15 dark:text-amber-300' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            @if ($suratTerbit)
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                      d="M12 6v6l4 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @endif
                        </svg>
                    </span>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wide {{ $suratTerbit ? 'text-emerald-700/80 dark:text-emerald-300/80' : 'text-amber-700/80 dark:text-amber-300/80' }}">
                            Surat balasan
                        </dt>
                        <dd class="mt-0.5 text-sm font-bold {{ $suratTerbit ? 'text-emerald-700 dark:text-emerald-300' : 'text-amber-700 dark:text-amber-300' }}">
                            {{ $suratTerbit ? 'Telah diterbitkan, siap diunduh' : 'Sedang diproses' }}
                        </dd>
                    </div>
                </div>
            </dl>

            <div class="mt-6 flex gap-2.5">
                <a id="popLihatStatus" href="{{ route('user.pendaftaran.status') }}"
                   class="inline-flex min-h-12 flex-1 items-center justify-center rounded-xl bg-emerald-600 px-4 text-sm font-bold text-white
                          transition hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400
                          dark:bg-emerald-400 dark:text-emerald-950 dark:hover:bg-emerald-300">
                    Lihat Status
                </a>
                <button type="button" id="tutupDiterima"
                        class="inline-flex min-h-12 flex-1 items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-4
                               text-sm font-bold text-slate-700 transition hover:bg-slate-200 focus:outline-none
                               focus-visible:ring-2 focus-visible:ring-slate-400
                               dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Tampil sekali per pendaftaran di browser ini.
    (() => {
        const kunci = 'diterima-dilihat-{{ $pendaftaran->id }}-{{ $suratTerbit ? 'terbit' : 'proses' }}';
        const overlay = document.getElementById('modalDiterima');

        try {
            if (localStorage.getItem(kunci)) return;
            localStorage.setItem(kunci, '1');
        } catch (e) {}

        const buka = () => {
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            document.documentElement.style.overflow = 'hidden';
            document.getElementById('popLihatStatus').focus();
        };
        const tutup = () => {
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
            document.documentElement.style.overflow = '';
        };

        document.getElementById('tutupDiterima').addEventListener('click', tutup);
        overlay.addEventListener('click', e => { if (e.target === overlay) tutup(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') tutup(); });

        buka();
    })();
</script>