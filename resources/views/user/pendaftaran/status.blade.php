@extends('user.layout.app')

@section('title', 'Status Pendaftaran')

@section('content')
<x-page>

    <x-page-header
        eyebrow="Pendaftaran"
        title="Status Pendaftaran"
        :back="route('user.dashboard')"
    >
        Pantau progres verifikasi pengajuan magang/PKL kamu.

        <x-slot:action>
            <x-button :href="route('user.pendaftaran.riwayat')" variant="secondary" size="sm">
                Lihat Riwayat
            </x-button>
        </x-slot:action>
    </x-page-header>

    @if(session('success'))
        <x-alert type="success">✨ {{ session('success') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @endif

    @if(!$pendaftaran)

        {{-- BELUM PERNAH MENDAFTAR --}}
        <x-card class="text-center !py-12">
            <div class="text-3xl mb-3">📭</div>
            <h2 class="text-base font-semibold text-slate-900">Belum Ada Pendaftaran</h2>
            <p class="text-sm text-slate-500 mt-1 mb-5">Kamu belum pernah mengajukan pendaftaran magang atau PKL.</p>
            <x-button :href="route('user.pendaftaran.create')">Daftar Sekarang</x-button>
        </x-card>

    @else

        @php
            $status = $pendaftaran->status;

            // Tiga langkah: dikirim -> verifikasi -> keputusan
            $langkah = [
                ['label' => 'Data Dikirim', 'state' => $status === 'draft' ? 'active' : 'done'],
                ['label' => 'Verifikasi',   'state' => match (true) {
                    $status === 'draft'                       => 'todo',
                    in_array($status, ['pending', 'revisi'])  => 'active',
                    default                                   => 'done',
                }],
                ['label' => 'Keputusan',    'state' => match ($status) {
                    'diterima', 'selesai' => 'done',
                    'ditolak'             => 'rejected',
                    default               => 'todo',
                }],
            ];

            $warnaBulat = [
                'done'     => 'bg-emerald-500 text-white',
                'active'   => 'bg-blue-600 text-white animate-pulse',
                'rejected' => 'bg-rose-500 text-white',
                'todo'     => 'bg-slate-200 text-slate-400',
            ];
            $ikonBulat = ['done' => '✓', 'active' => '●', 'rejected' => '✕', 'todo' => '○'];

            // Warna garis penghubung = warna langkah tujuannya
            $warnaGaris = fn ($state) => match ($state) {
                'done', 'active' => 'bg-emerald-500',
                'rejected'       => 'bg-rose-400',
                default          => 'bg-slate-200',
            };

            $bolehBatal = in_array($status, ['draft', 'pending', 'revisi', 'ditolak']);
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {{-- ===== KARTU UTAMA ===== --}}
            <x-card :pad="false" class="lg:col-span-2">

                {{-- Header --}}
                <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="text-sm font-semibold text-slate-900">Pendaftaran #{{ $nomorUrut }}</span>
                        <x-status-badge :status="$status" />
                    </div>
                    <span class="text-xs text-slate-400 whitespace-nowrap">{{ $pendaftaran->created_at->translatedFormat('d M Y') }}</span>
                </div>

                {{-- Stepper (status revisi memakai kartu sendiri di bawah) --}}
                @if($status !== 'revisi')
                    <div class="px-5 sm:px-6 py-5 border-b border-slate-100">
                        <div class="flex items-start max-w-md mx-auto">
                            @foreach($langkah as $i => $item)
                                @if($i > 0)
                                    <div class="flex-1 h-0.5 mt-[13px] mx-1 rounded-full {{ $warnaGaris($item['state']) }}"></div>
                                @endif

                                <div class="w-16 flex flex-col items-center shrink-0">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold {{ $warnaBulat[$item['state']] }}">
                                        {{ $ikonBulat[$item['state']] }}
                                    </div>
                                    <span class="text-xs font-medium text-slate-600 mt-2 text-center leading-tight">{{ $item['label'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Isi sesuai status --}}
                <div class="px-5 sm:px-6 py-5">

                    @if($status === 'pending')
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0">⏳</div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">Sedang dalam proses verifikasi</h3>
                                <p class="text-sm text-slate-500 mt-1 leading-relaxed">Admin sedang memeriksa kelengkapan data dan dokumen kamu. Kamu akan diberi tahu begitu ada keputusan.</p>
                            </div>
                        </div>
                        <p class="mt-5 pt-4 border-t border-slate-100 text-xs text-slate-400">
                            Data pendaftaran tidak dapat diubah selama masih dalam proses verifikasi.
                        </p>

                    @elseif($status === 'revisi')
                        <div class="p-4 sm:p-5 bg-orange-50 border border-orange-200 rounded-xl">
                            <h3 class="text-base font-semibold text-orange-800">⚠️ Perlu Perbaikan</h3>
                            <p class="text-sm text-orange-900/80 mt-1 mb-4">Silakan perbaiki data sesuai catatan admin di bawah ini.</p>
                            <div class="p-4 bg-white border border-orange-100 rounded-lg">
                                <p class="text-[11px] font-semibold text-orange-700 uppercase tracking-wide mb-1">Alasan revisi</p>
                                <p class="text-sm text-slate-700 leading-relaxed">"{{ $pendaftaran->alasan_revisi ?? 'Tidak ada catatan khusus.' }}"</p>
                            </div>
                            <x-button :href="route('user.pendaftaran.edit', $pendaftaran->id)" variant="orange" class="mt-4">
                                Perbaiki Pendaftaran
                            </x-button>
                        </div>

                    @elseif($status === 'diterima')
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0">✅</div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">Selamat, pendaftaran kamu diterima!</h3>
                                <p class="text-sm text-slate-500 mt-1 leading-relaxed">Pengajuan magang/PKL kamu telah disetujui oleh instansi tujuan.</p>
                            </div>
                        </div>
                        @if($pendaftaran->surat_balasan ?? null)
                            <x-button :href="asset('storage/' . $pendaftaran->surat_balasan)" target="_blank" variant="success" class="mt-5">
                                Unduh Surat Balasan
                            </x-button>
                        @endif

                    @elseif($status === 'ditolak')
                        <div class="p-4 sm:p-5 bg-rose-50 border border-rose-200 rounded-xl">
                            <h3 class="text-base font-semibold text-rose-800">❌ Pendaftaran Ditolak</h3>
                            <div class="mt-3 p-4 bg-white border border-rose-100 rounded-lg">
                                <p class="text-[11px] font-semibold text-rose-700 uppercase tracking-wide mb-1">Alasan penolakan</p>
                                <p class="text-sm text-slate-700 leading-relaxed">"{{ $pendaftaran->alasan_penolakan ?? 'Tidak ada catatan khusus.' }}"</p>
                            </div>
                            <x-button :href="route('user.pendaftaran.create')" class="mt-4">
                                Daftar Magang Lagi
                            </x-button>
                        </div>

                    @elseif($status === 'selesai')
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg shrink-0">🏁</div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">Magang telah selesai</h3>
                                <p class="text-sm text-slate-500 mt-1 leading-relaxed">Periode magang/PKL kamu di instansi ini sudah berakhir. Terima kasih atas kontribusinya!</p>
                            </div>
                        </div>

                    @elseif($status === 'draft')
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-lg shrink-0">📝</div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">Masih draft, belum dikirim</h3>
                                <p class="text-sm text-slate-500 mt-1 leading-relaxed">Lanjutkan mengisi formulir dan kirim pengajuan kalau sudah yakin datanya benar.</p>
                            </div>
                        </div>
                        <x-button :href="route('user.pendaftaran.edit', $pendaftaran->id)" variant="warning" class="mt-5">
                            Lanjutkan Draft
                        </x-button>
                    @endif
                </div>
            </x-card>

            {{-- ===== DETAIL PENGAJUAN ===== --}}
            <x-card class="lg:col-span-1">
                <h2 class="text-sm font-semibold text-slate-900">Detail Pengajuan</h2>

                <dl class="mt-4 space-y-3.5">
                    @foreach([
                        'Instansi' => $pendaftaran->dinas_tujuan,
                        'Bidang'   => $pendaftaran->divisi,
                        'Kategori' => $pendaftaran->kategori,
                        'Periode'  => ($pendaftaran->tanggal_mulai && $pendaftaran->tanggal_selesai)
                                        ? \Carbon\Carbon::parse($pendaftaran->tanggal_mulai)->translatedFormat('d M Y')
                                          . ' – '
                                          . \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->translatedFormat('d M Y')
                                        : null,
                        'Diajukan' => $pendaftaran->created_at->translatedFormat('d M Y, H:i'),
                    ] as $label => $nilai)
                        @if(filled($nilai))
                            <div>
                                <dt class="text-[11px] font-medium uppercase tracking-wide text-slate-400">{{ $label }}</dt>
                                <dd class="mt-0.5 text-sm font-medium text-slate-800 break-words">{{ $nilai }}</dd>
                            </div>
                        @endif
                    @endforeach
                </dl>

                @if($bolehBatal)
                    <form action="{{ route('user.pendaftaran.destroy', $pendaftaran->id) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin membatalkan pengajuan ini?')"
                          class="mt-5 pt-5 border-t border-slate-100">
                        @csrf
                        @method('DELETE')
                        <x-button type="submit" variant="danger" size="sm" class="w-full">
                            {{ $status === 'draft' ? 'Hapus Draft' : 'Batalkan Pengajuan' }}
                        </x-button>
                    </form>
                @endif
            </x-card>
        </div>

    @endif

</x-page>
@endsection