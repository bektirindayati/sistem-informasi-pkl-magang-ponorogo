@extends('admin.layout.app')

@section('page-title', 'Detail Pendaftar')

@section('content')

    @php
        $status = strtolower($pendaftaran->status ?? '');
        $statusMeta = [
            'draft'    => ['label' => 'Draft', 'class' => 'bg-slate-100 text-slate-700'],
            'pending'  => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-amber-100 text-amber-700'],
            'revisi'   => ['label' => 'Perlu Revisi', 'class' => 'bg-orange-100 text-orange-700'],
            'diterima' => ['label' => 'Diterima', 'class' => 'bg-emerald-100 text-emerald-700'],
            'ditolak'  => ['label' => 'Ditolak', 'class' => 'bg-rose-100 text-rose-700'],
            'selesai'  => ['label' => 'Selesai', 'class' => 'bg-indigo-100 text-indigo-700'],
        ][$status] ?? ['label' => ucfirst($status), 'class' => 'bg-slate-100 text-slate-700'];
    @endphp

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('admin.pendaftar.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Pendaftar
        </a>
        <span class="px-3 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wide {{ $statusMeta['class'] }}">{{ $statusMeta['label'] }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KOLOM KIRI: DATA DIRI --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-extrabold text-slate-900 text-base mb-4">Data Diri & Akademik</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div><dt class="text-xs text-slate-400 font-bold uppercase tracking-wide">Nama Lengkap</dt><dd class="text-slate-800 font-semibold mt-0.5">{{ $pendaftaran->nama_lengkap }}</dd></div>
                    <div><dt class="text-xs text-slate-400 font-bold uppercase tracking-wide">Kategori</dt><dd class="text-slate-800 font-semibold mt-0.5">{{ $pendaftaran->kategori ?? '-' }}</dd></div>
                    <div><dt class="text-xs text-slate-400 font-bold uppercase tracking-wide">NIM / NISN</dt><dd class="text-slate-800 font-semibold mt-0.5">{{ $pendaftaran->nim_nisn }}</dd></div>
                    <div><dt class="text-xs text-slate-400 font-bold uppercase tracking-wide">No. WhatsApp / HP</dt><dd class="text-slate-800 font-semibold mt-0.5">{{ $pendaftaran->no_hp }}</dd></div>
                    <div><dt class="text-xs text-slate-400 font-bold uppercase tracking-wide">Asal Instansi</dt><dd class="text-slate-800 font-semibold mt-0.5">{{ $pendaftaran->instansi }}</dd></div>
                    <div><dt class="text-xs text-slate-400 font-bold uppercase tracking-wide">Jurusan</dt><dd class="text-slate-800 font-semibold mt-0.5">{{ $pendaftaran->jurusan }}</dd></div>
                    <div class="sm:col-span-2"><dt class="text-xs text-slate-400 font-bold uppercase tracking-wide">Alamat</dt><dd class="text-slate-800 font-semibold mt-0.5">{{ $pendaftaran->alamat }}, {{ $pendaftaran->kabupaten }}, {{ $pendaftaran->provinsi }}</dd></div>
                </dl>
            </div>

            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-extrabold text-slate-900 text-base mb-4">Instansi Tujuan & Periode</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div><dt class="text-xs text-slate-400 font-bold uppercase tracking-wide">Dinas Tujuan</dt><dd class="text-slate-800 font-semibold mt-0.5">{{ $pendaftaran->dinas_tujuan ?? '-' }}</dd></div>
                    <div><dt class="text-xs text-slate-400 font-bold uppercase tracking-wide">Bidang</dt><dd class="text-slate-800 font-semibold mt-0.5">{{ $pendaftaran->divisi ?? '-' }}</dd></div>
                    <div><dt class="text-xs text-slate-400 font-bold uppercase tracking-wide">Tanggal Mulai</dt><dd class="text-slate-800 font-semibold mt-0.5">{{ $pendaftaran->tanggal_mulai ? \Carbon\Carbon::parse($pendaftaran->tanggal_mulai)->translatedFormat('d M Y') : '-' }}</dd></div>
                    <div><dt class="text-xs text-slate-400 font-bold uppercase tracking-wide">Tanggal Selesai</dt><dd class="text-slate-800 font-semibold mt-0.5">{{ $pendaftaran->tanggal_selesai ? \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->translatedFormat('d M Y') : '-' }}</dd></div>
                </dl>
            </div>

            {{-- BERKAS --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-extrabold text-slate-900 text-base mb-4">Berkas Terlampir</h3>
                <div class="flex flex-wrap gap-3">
                    <x-admin.file-link :href="$pendaftaran->surat_pengantar ? asset('storage/' . $pendaftaran->surat_pengantar) : null" icon="📄" label="Surat Pengantar" color="blue" />
                    <x-admin.file-link :href="$pendaftaran->proposal ? asset('storage/' . $pendaftaran->proposal) : null" icon="📑" label="Proposal Magang" color="indigo" empty-text="Tidak ada proposal" />
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: AKSI VERIFIKASI --}}
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-extrabold text-slate-900 text-base mb-4">Ubah Status</h3>
                <x-admin.status-select
                    :status="$pendaftaran->status"
                    :action="route('admin.verifikasi.update', $pendaftaran->id)"
                    :alasan-penolakan="$pendaftaran->alasan_penolakan ?? null"
                    :alasan-revisi="$pendaftaran->alasan_revisi ?? null" />

                {{--
                    Alasan penolakan/revisi ditaruh di sini (menempel
                    dengan aksi "Ubah Status" yang menghasilkannya),
                    bukan lagi di tengah kolom kiri di antara data
                    instansi & berkas.
                --}}
                @if($status === 'revisi' && $pendaftaran->alasan_revisi)
                    <div class="mt-4 bg-orange-50 border border-orange-200 rounded-2xl p-4">
                        <p class="text-xs font-bold text-orange-700 uppercase tracking-wide mb-1">Alasan Revisi</p>
                        <p class="text-sm text-orange-900">{{ $pendaftaran->alasan_revisi }}</p>
                    </div>
                @elseif($status === 'ditolak' && $pendaftaran->alasan_penolakan)
                    <div class="mt-4 bg-rose-50 border border-rose-200 rounded-2xl p-4">
                        <p class="text-xs font-bold text-rose-700 uppercase tracking-wide mb-1">Alasan Penolakan</p>
                        <p class="text-sm text-rose-900">{{ $pendaftaran->alasan_penolakan }}</p>
                    </div>
                @endif
            </div>

            @if($status === 'diterima')
                <a href="{{ route('admin.pendaftar.download', $pendaftaran->id) }}" class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-sm transition">
                    📄 Cetak Surat Balasan
                </a>
            @endif

            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs text-slate-500">
                Diajukan: {{ $pendaftaran->created_at->translatedFormat('d M Y, H:i') }}<br>
                Terakhir diperbarui: {{ $pendaftaran->updated_at->translatedFormat('d M Y, H:i') }}
            </div>
        </div>
    </div>

@endsection