@extends('admin.layout.app')

@section('page-title', 'Periksa Surat Balasan')

@section('content')

@php
    $status   = $surat->status;               // draft | menunggu_ttd | terbit
    $terkunci = $status !== 'draft';          // form hanya bisa diubah saat draft

    $statusUi = [
        'draft'        => ['label' => 'Draft',        'class' => 'bg-slate-100 text-slate-700 border-slate-200'],
        'menunggu_ttd' => ['label' => 'Menunggu TTD', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
        'terbit'       => ['label' => 'Terbit',       'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
    ][$status];

    // [name, label, type, wajib]
    $fields = [
        ['nomor_surat',          'Nomor Surat Dinas',                 'text', false],
        ['tanggal_surat',        'Tanggal Surat',                     'date', true],
        ['universitas',          'Asal Instansi / Universitas',       'text', true],
        ['pejabat_kampus',       'Jabatan Penanda Tangan Surat Kampus', 'text', true],
        ['nomor_surat_kampus',   'Nomor Surat Kampus',                'text', false],
        ['tanggal_surat_kampus', 'Tanggal Surat Kampus',              'date', false],
        ['nama',                 'Nama',                              'text', true],
        ['nim',                  'NIM / NISN',                        'text', true],
        ['prodi',                'Prodi / Jurusan',                   'text', true],
        ['sebutan',              'Sebutan (mahasiswa / siswa)',       'text', true],
        ['tanggal_mulai',        'Tanggal Mulai Magang',              'date', true],
        ['tanggal_selesai',      'Tanggal Selesai Magang',            'date', true],
    ];

    $nilai = function (string $name, string $type) use ($surat) {
        $v = old($name, $surat->{$name});
        return ($type === 'date' && $v instanceof \Carbon\CarbonInterface) ? $v->format('Y-m-d') : $v;
    };

    $btn = 'inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold transition';
@endphp

<div class="max-w-4xl mx-auto space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <a href="{{ route('admin.pendaftar.show', $surat->pendaftaran_magang_id) }}"
               class="text-xs font-bold text-slate-400 hover:text-cyan-700 transition">← Kembali ke detail pendaftar</a>
            <h2 class="mt-1 text-xl md:text-2xl font-extrabold text-slate-800">Surat Balasan Magang</h2>
            <p class="text-xs text-slate-500 font-semibold mt-0.5">{{ $surat->nama }} · {{ $surat->universitas }}</p>
        </div>
        <span class="self-start sm:self-auto inline-flex items-center px-3 py-1.5 rounded-full border text-[11px] font-extrabold uppercase tracking-wide {{ $statusUi['class'] }}">
            {{ $statusUi['label'] }}
        </span>
    </div>

    {{-- Alur / langkah --}}
    <ol class="grid grid-cols-3 gap-2 text-[11px] font-bold text-center">
        @foreach ([['draft', '1. Periksa & koreksi'], ['menunggu_ttd', '2. Tanda tangan BSrE'], ['terbit', '3. Terbit']] as [$kode, $teks])
            @php
                $urutan = ['draft' => 0, 'menunggu_ttd' => 1, 'terbit' => 2];
                $aktif  = $urutan[$status] >= $urutan[$kode];
            @endphp
            <li class="rounded-xl border px-2 py-2 {{ $aktif ? 'bg-cyan-50 border-cyan-200 text-cyan-700' : 'bg-white border-slate-200 text-slate-400' }}">
                {{ $teks }}
            </li>
        @endforeach
    </ol>

    {{-- Pesan --}}
    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            <p class="font-bold mb-1">Periksa kembali isian berikut:</p>
            <ul class="list-disc pl-5 text-xs space-y-0.5">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    @if ($terkunci)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800 font-semibold">
            Data dikunci karena surat sudah
            {{ $status === 'terbit' ? 'terbit' : 'disetujui dan menunggu tanda tangan' }}.
            Untuk mengubah data, kembalikan surat ke draft (PDF yang sudah ditandatangani perlu dibuat ulang).
        </div>
    @endif

    {{-- Form data surat --}}
    <form method="POST" action="{{ route('admin.surat.update', $surat) }}"
          class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5 md:p-7">
        @csrf
        @method('PUT')

        <h3 class="font-extrabold text-slate-900 text-base">Data Surat</h3>
        <p class="text-xs text-slate-500 font-semibold mt-0.5">
            Koreksi dilakukan pada data ini, bukan pada PDF. Setelah disimpan, buat pratinjau lagi.
        </p>

        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach ($fields as [$name, $label, $type, $wajib])
                <div>
                    <label for="{{ $name }}" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                        {{ $label }} @if ($wajib)<span class="text-rose-500">*</span>@endif
                    </label>
                    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
                           value="{{ $nilai($name, $type) }}"
                           @disabled($terkunci)
                           class="w-full rounded-xl border px-3.5 py-2.5 text-sm text-slate-800 transition
                                  focus:outline-none focus:ring-2 focus:ring-cyan-200 focus:border-cyan-400
                                  disabled:bg-slate-50 disabled:text-slate-400 disabled:cursor-not-allowed
                                  {{ $errors->has($name) ? 'border-rose-300' : 'border-slate-200' }}">
                    @error($name)
                        <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        @unless ($terkunci)
            <div class="mt-6 pt-5 border-t border-slate-100 flex flex-col sm:flex-row gap-2.5">
                <button type="submit" class="{{ $btn }} bg-cyan-600 hover:bg-cyan-700 text-white shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        @endunless
    </form>

    {{-- Aksi sesuai status --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-5 md:p-7">
        <h3 class="font-extrabold text-slate-900 text-base">Langkah Berikutnya</h3>

        {{-- DRAFT --}}
        @if ($status === 'draft')
            <p class="text-xs text-slate-500 font-semibold mt-0.5">
                Simpan dulu bila ada perubahan, lalu pratinjau. Jika sudah benar, setujui untuk mengunduh PDF yang akan ditandatangani.
            </p>
            <div class="mt-4 flex flex-col sm:flex-row gap-2.5">
                <a href="{{ route('admin.surat.pratinjau', $surat) }}" target="_blank" rel="noopener"
                   class="{{ $btn }} bg-slate-100 hover:bg-slate-200 text-slate-700">
                    Pratinjau PDF
                </a>

                <form method="POST" action="{{ route('admin.surat.setujui', $surat) }}"
                      onsubmit="return confirm('Data surat akan dikunci dan PDF diunduh untuk ditandatangani. Lanjutkan?')">
                    @csrf
                    <button type="submit" class="{{ $btn }} w-full bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm">
                        Setujui &amp; Unduh PDF
                    </button>
                </form>
            </div>
        @endif

        {{-- MENUNGGU TTD --}}
        @if ($status === 'menunggu_ttd')
            <p class="text-xs text-slate-500 font-semibold mt-0.5">
                Tanda tangani PDF melalui aplikasi BSrE, lalu unggah file yang sudah bertanda tangan di sini.
            </p>

            <div class="mt-4 flex flex-col sm:flex-row gap-2.5">
                <form method="POST" action="{{ route('admin.surat.setujui', $surat) }}">
                    @csrf
                    <button type="submit" class="{{ $btn }} w-full bg-slate-100 hover:bg-slate-200 text-slate-700">
                        Unduh Ulang PDF (belum TTD)
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.surat.buka', $surat) }}"
                      onsubmit="return confirm('Kembalikan ke draft? Anda perlu menyetujui dan menandatangani ulang.')">
                    @csrf
                    <button type="submit" class="{{ $btn }} w-full bg-white border border-slate-200 hover:border-amber-300 hover:text-amber-700 text-slate-600">
                        Kembalikan ke Draft
                    </button>
                </form>
            </div>

            <form method="POST" action="{{ route('admin.surat.terbitkan', $surat) }}" enctype="multipart/form-data"
                  class="mt-5 pt-5 border-t border-slate-100">
                @csrf
                <label for="pdf_ttd" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                    PDF bertanda tangan (maks. 5 MB) <span class="text-rose-500">*</span>
                </label>
                <input id="pdf_ttd" name="pdf_ttd" type="file" accept="application/pdf" required
                       class="block w-full text-sm text-slate-600 rounded-xl border border-slate-200 bg-slate-50
                              file:mr-3 file:px-4 file:py-2.5 file:border-0 file:bg-cyan-600 file:text-white
                              file:text-xs file:font-bold hover:file:bg-cyan-700">
                @error('pdf_ttd')
                    <p class="text-[11px] text-rose-600 font-semibold mt-1">{{ $message }}</p>
                @enderror

                <button type="submit" class="{{ $btn }} mt-3 bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm"
                        onclick="return confirm('Surat akan diterbitkan dan peserta dapat mengunduhnya. Pastikan PDF sudah benar-benar ditandatangani.')">
                    Terbitkan Surat
                </button>
            </form>
        @endif

        {{-- TERBIT --}}
        @if ($status === 'terbit')
            <p class="text-xs text-slate-500 font-semibold mt-0.5">
                Diterbitkan {{ $surat->diterbitkan_at?->locale('id')->translatedFormat('d F Y, H:i') }}.
                Peserta kini dapat mengunduh surat dari dashboard mereka.
            </p>
            <div class="mt-4 flex flex-col sm:flex-row gap-2.5">
                <a href="{{ route('admin.surat.unduh', $surat) }}"
                   class="{{ $btn }} bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm">
                    Unduh PDF Terbit
                </a>

                <form method="POST" action="{{ route('admin.surat.buka', $surat) }}"
                      onsubmit="return confirm('Surat yang sudah terbit akan dikembalikan ke draft dan tidak bisa diunduh peserta sampai diterbitkan ulang. Lanjutkan?')">
                    @csrf
                    <button type="submit" class="{{ $btn }} w-full bg-white border border-slate-200 hover:border-amber-300 hover:text-amber-700 text-slate-600">
                        Terbitkan Ulang
                    </button>
                </form>
            </div>
        @endif
    </div>

</div>

@endsection