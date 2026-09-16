@extends('admin.layout.app')

@section('page-title', 'Verifikasi Berkas Masuk')

@section('content')

    {{-- PENCARIAN --}}
    <form
        method="GET"
        class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex flex-col sm:flex-row gap-3"
    >
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari nama pendaftar..."
            class="flex-1 min-w-0 px-4 py-2.5 rounded-xl border border-slate-200 text-sm
                   text-slate-700 placeholder:text-slate-400
                   focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100"
        >

        <div class="flex gap-2">
            <button
                type="submit"
                class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900
                       text-white text-sm font-bold rounded-xl transition"
            >
                Cari
            </button>

            @if(request('search'))
                <a
                    href="{{ route('admin.verifikasi.index') }}"
                    class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200
                           text-slate-600 text-sm font-bold rounded-xl transition"
                >
                    Reset
                </a>
            @endif
        </div>
    </form>


    {{-- DAFTAR PENGAJUAN --}}
    <x-admin.table-card
        icon="📋"
        title="Daftar Pengajuan Magang"
        description="Ubah status langsung melalui pilihan status pada tabel."
    >
        <table class="w-full text-left border-collapse min-w-[680px]">

            <thead>
                <tr class="bg-slate-50 border-b border-slate-200
                           text-slate-500 font-bold text-xs uppercase tracking-wider">

                    <th class="p-4 px-6">
                        Nama Pendaftar
                    </th>

                    <th class="p-4 px-6">
                        Instansi Tujuan & Periode
                    </th>

                    <th class="p-4 px-6">
                        Status Verifikasi
                    </th>

                    <th class="p-4 px-6 text-center">
                        Detail
                    </th>

                </tr>
            </thead>


            <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">

                @forelse($pendaftars ?? [] as $item)

                    <tr class="hover:bg-slate-50/70 transition">

                        {{-- NAMA --}}
                        <td class="p-4 px-6 align-top">

                            <a
                                href="{{ route('admin.pendaftar.show', $item->id) }}"
                                class="font-bold text-slate-900 hover:text-cyan-600 transition"
                            >
                                {{ $item->nama_lengkap }}
                            </a>

                            <p class="text-xs text-slate-500 font-semibold mt-1">
                                {{ $item->kategori ?? '-' }}
                                <span class="text-slate-300 mx-1">•</span>
                                {{ $item->instansi ?? '-' }}
                            </p>

                        </td>


                        {{-- INSTANSI --}}
                        <td class="p-4 px-6 align-top">

                            <p class="text-slate-700 font-semibold">
                                {{ $item->dinas_tujuan ?? '-' }}
                            </p>

                            <p class="text-xs text-slate-500 mt-1">
                                {{ $item->divisi ?? '-' }}
                            </p>

                            <p class="text-xs text-slate-400 mt-1 whitespace-nowrap">

                                {{ $item->tanggal_mulai
                                    ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y')
                                    : '-' }}

                                &ndash;

                                {{ $item->tanggal_selesai
                                    ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y')
                                    : '-' }}

                            </p>

                        </td>


                        {{-- STATUS --}}
                        <td class="p-4 px-6 align-top">

                            <x-admin.status-select
                                :status="$item->status"
                                :action="route('admin.verifikasi.update', $item->id)"
                                :alasan-penolakan="$item->alasan_penolakan ?? null"
                                :alasan-revisi="$item->alasan_revisi ?? null"
                            />

                        </td>


                        {{-- DETAIL --}}
                        <td class="p-4 px-6 align-top text-center">

                            <a
                                href="{{ route('admin.pendaftar.show', $item->id) }}"
                                class="inline-flex items-center justify-center
                                       px-3.5 py-2
                                       bg-slate-100 hover:bg-cyan-50
                                       text-slate-700 hover:text-cyan-700
                                       rounded-xl text-xs font-bold transition"
                            >
                                Detail
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="4"
                            class="p-12 text-center text-slate-400 font-bold"
                        >
                            <div class="flex flex-col items-center justify-center gap-2">

                                <span class="text-3xl">📭</span>

                                <span>
                                    {{ request('search')
                                        ? 'Tidak ada hasil untuk pencarian ini.'
                                        : 'Tidak ada berkas yang perlu diverifikasi saat ini.'
                                    }}
                                </span>

                            </div>
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </x-admin.table-card>


    {{-- PAGINATION --}}
    @if(isset($pendaftars) && method_exists($pendaftars, 'links'))
        <div>
            {{ $pendaftars->links() }}
        </div>
    @endif

@endsection