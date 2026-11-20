{{-- <x-statistik-magang :stat="$statistik" :lingkup="$lingkup" /> : kartu ringkasan + grafik 5 tahun --}}
@props(['stat'])

@php
    $kartu = [
        [
            'judul' => 'Total Pendaftar', 'sub' => 'Seluruh pengajuan', 'nilai' => $stat['totalPendaftar'],
            'satuan' => 'pengajuan', 'ket' => 'Tercatat di sistem', 'ikon' => 'users',
            'bar' => 'bg-[#2F5BFF]',
            'chip' => 'bg-[#2F5BFF]/10 border-[#2F5BFF]/15 text-[#2F5BFF] dark:border-blue-400/20 dark:text-blue-400',
            'bayang' => 'text-[#2F5BFF]/[0.06] dark:text-blue-400/[0.08]',
            'hover' => 'hover:shadow-[0_10px_30px_rgba(47,91,255,0.12)]',
        ],
        [
            'judul' => 'Pemagang Aktif', 'sub' => 'Sedang menjalani magang', 'nilai' => $stat['pemagangAktif'],
            'satuan' => 'orang', 'ket' => 'Status diterima dan periode berjalan', 'ikon' => 'user-check',
            'bar' => 'bg-emerald-500',
            'chip' => 'bg-emerald-50 border-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:border-emerald-500/20 dark:text-emerald-400',
            'bayang' => 'text-emerald-500/[0.07] dark:text-emerald-400/[0.08]',
            'hover' => 'hover:shadow-[0_10px_30px_rgba(16,185,129,0.12)]',
        ],
        [
            'judul' => 'Alumni Magang', 'sub' => 'Telah selesai periode', 'nilai' => $stat['alumniSelesai'],
            'satuan' => 'orang', 'ket' => 'Riwayat pemagang yang sudah tuntas', 'ikon' => 'cap',
            'bar' => 'bg-slate-500',
            'chip' => 'bg-slate-100 border-slate-200 text-slate-600 dark:bg-slate-700 dark:border-slate-600 dark:text-slate-300',
            'bayang' => 'text-slate-500/[0.07] dark:text-slate-400/[0.08]',
            'hover' => 'hover:shadow-[0_10px_30px_rgba(71,85,105,0.12)]',
        ],
    ];
@endphp

<div class="space-y-5">

    {{-- KARTU RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($kartu as $k)
            <div class="group relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl p-5
                        border border-slate-200 dark:border-slate-700
                        shadow-[0_4px_20px_rgba(15,23,42,0.04)] dark:shadow-none {{ $k['hover'] }}
                        hover:-translate-y-0.5 transition-all duration-200">

                <x-ikon :name="$k['ikon']" class="absolute -right-4 -bottom-4 w-28 h-28 pointer-events-none {{ $k['bayang'] }}" />
                <div class="absolute left-0 top-0 bottom-0 w-1 {{ $k['bar'] }}"></div>

                <div class="relative flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl border flex items-center justify-center shrink-0 {{ $k['chip'] }}">
                        <x-ikon :name="$k['ikon']" class="w-5 h-5" />
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $k['judul'] }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $k['sub'] }}</p>
                    </div>
                </div>

                <div class="relative mt-4 flex items-baseline gap-2">
                    <h3 class="text-3xl font-extrabold text-slate-900 dark:text-white leading-none tracking-tight">{{ $k['nilai'] }}</h3>
                    <span class="text-xs font-semibold text-slate-400">{{ $k['satuan'] }}</span>
                </div>
                <p class="relative text-xs text-slate-400 mt-1.5">{{ $k['ket'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- GRAFIK --}}
    <div class="relative w-full min-w-0 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700
                shadow-[0_4px_20px_rgba(15,23,42,0.04)] dark:shadow-none overflow-hidden">

        <div class="px-6 pt-5 pb-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-slate-100 dark:border-slate-700">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#2F5BFF]/10 border border-[#2F5BFF]/15 dark:border-blue-400/20 flex items-center justify-center">
                    <x-ikon name="chart" class="w-5 h-5 text-[#2F5BFF] dark:text-blue-400" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Perkembangan Pendaftar &amp; Pemagang</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Perbandingan jumlah pengajuan dan pengajuan yang diterima setiap tahun</p>
                </div>
            </div>

            <div class="flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-200 dark:border-slate-700 text-sm text-slate-600 dark:text-slate-300 w-fit">
                <x-ikon name="calendar" class="w-4 h-4 text-slate-500 dark:text-slate-400" />
                <span class="font-medium">5 Tahun Terakhir</span>
            </div>
        </div>

        <div class="px-5 pb-5 pt-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 min-w-0 items-stretch">

                <div class="lg:col-span-3 rounded-xl bg-slate-50/70 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700 p-4">
                    <div class="relative h-[300px]">
                        <canvas id="statistikMagangChart"></canvas>
                    </div>
                </div>

                <div class="relative overflow-hidden rounded-xl bg-[#2F5BFF]/[0.04] dark:bg-blue-500/10 border border-[#2F5BFF]/10 dark:border-blue-400/15 p-5">
                    <h4 class="text-base font-bold text-[#2F5BFF] dark:text-blue-400 mb-5">Ringkasan Tahun {{ $stat['tahunSekarang'] }}</h4>

                    @foreach ([['Pendaftar', '#6385F5', $stat['pendaftarTahunIni']], ['Diterima', '#F45B91', $stat['diterimaTahunIni']]] as [$nama, $warna, $nilai])
                        <div class="flex items-center justify-between py-4 first:pt-0 border-b border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-2.5">
                                <span class="w-3 h-3 rounded-full" style="background: {{ $warna }}"></span>
                                <span class="text-sm text-slate-600 dark:text-slate-300">{{ $nama }}</span>
                            </div>
                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ $nilai }}</span>
                        </div>
                    @endforeach

                    <div class="mt-5">
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-4">Tingkat Diterima</p>

                        <div class="flex items-center gap-3">
                            <div class="relative w-20 h-20 shrink-0">
                                <svg class="w-20 h-20" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="9" class="text-slate-200 dark:text-slate-700"/>
                                    <circle cx="50" cy="50" r="40" fill="none" stroke="#ec4899" stroke-width="9" stroke-linecap="round"
                                            stroke-dasharray="251.2"
                                            stroke-dashoffset="{{ 251.2 - (251.2 * $stat['persentaseDiterima'] / 100) }}"
                                            transform="rotate(-90 50 50)"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-sm font-bold text-pink-500">{{ $stat['persentaseDiterima'] }}%</span>
                                </div>
                            </div>
                            <p class="text-xs leading-5 text-slate-500 dark:text-slate-400">
                                {{ $stat['diterimaTahunIni'] }} dari {{ $stat['pendaftarTahunIni'] }} pengajuan tahun ini telah diterima.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 px-4 py-3 rounded-xl bg-[#2F5BFF]/[0.05] dark:bg-blue-500/10 border border-[#2F5BFF]/10 dark:border-blue-400/15 flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-[#2F5BFF]/10 flex items-center justify-center shrink-0">
                    <x-ikon name="info" class="w-4 h-4 text-[#2F5BFF] dark:text-blue-400" />
                </div>
                <p class="text-xs text-[#2F5BFF] dark:text-blue-300">
                    Data diperbarui secara real-time dari sistem SiMagang.
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (() => {
        const data  = @json($stat['statistikMagang']);
        const gelap = () => document.documentElement.classList.contains('dark');
        const batang = { borderRadius: 7, borderSkipped: false, barPercentage: 0.65, categoryPercentage: 0.65 };

        const chart = new Chart(document.getElementById('statistikMagangChart'), {
            type: 'bar',
            data: {
                labels: data.map(d => d.tahun),
                datasets: [
                    { label: 'Pendaftar', data: data.map(d => Number(d.pendaftar)), backgroundColor: '#6385F5', ...batang },
                    { label: 'Diterima',  data: data.map(d => Number(d.diterima)),  backgroundColor: '#F45B91', ...batang },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, pointStyle: 'rectRounded', padding: 25, font: { size: 13 } } },
                    tooltip: { backgroundColor: '#172554', titleColor: '#ffffff', bodyColor: '#ffffff', padding: 12, cornerRadius: 10 },
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 13 } } },
                    y: { beginAtZero: true, ticks: { precision: 0, font: { size: 12 } }, grid: { borderDash: [5, 5] } },
                },
            },
        });

        // Warna grafik mengikuti mode terang/gelap, termasuk saat tema diganti.
        const terapkanTema = () => {
            const dark = gelap();
            chart.options.plugins.legend.labels.color = dark ? '#cbd5e1' : '#475569';
            chart.options.scales.x.ticks.color = dark ? '#cbd5e1' : '#475569';
            chart.options.scales.y.ticks.color = dark ? '#94a3b8' : '#64748b';
            chart.options.scales.y.grid.color  = dark ? '#334155' : '#e2e8f0';
            chart.update();
        };

        terapkanTema();
        new MutationObserver(terapkanTema).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    })();
</script>