@extends('admin.layout.app')

@section('page-title', 'Kelola Dinas')

@section('content')

    @php
        $jumlah = (isset($dinasList) && method_exists($dinasList, 'total')) ? $dinasList->total() : count($dinasList ?? []);
    @endphp

    @if(session('success'))
        <x-alert type="success"> {{ session('success') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @endif

    {{-- Form Tambah Dinas --}}
    <section class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm dark:shadow-none overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Tambah Dinas Baru</h3>
            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                Data kop surat (alamat, telepon, website, email) dipakai untuk mengisi draf surat balasan magang.
            </p>
        </div>

        <form action="{{ route('admin.dinas.store') }}" method="POST" class="p-6">
            @csrf
            @include('admin.dinas._form')
        </form>
    </section>

    {{-- Tabel Daftar Dinas --}}
    <x-admin.table-card title="Daftar Dinas Terdaftar" description="Dinas yang sudah terdaftar beserta kelengkapan kop suratnya.">
        <x-slot:actions>
            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-200 text-xs font-bold whitespace-nowrap">
                {{ $jumlah }} dinas
            </span>
        </x-slot:actions>

        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                    <th class="py-3 px-6 w-14">No</th>
                    <th class="py-3 px-6">Nama Dinas</th>
                    <th class="py-3 px-6">Alamat</th>
                    <th class="py-3 px-6">Kop Surat</th>
                    <th class="py-3 px-6">Status</th>
                    <th class="py-3 px-6 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-300">
                @forelse($dinasList ?? [] as $index => $dinas)
                    <tr class="align-top hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                        <td class="py-3.5 px-6 text-slate-500 dark:text-slate-400">
                            {{ method_exists($dinasList, 'firstItem') ? $dinasList->firstItem() + $index : $index + 1 }}
                        </td>
                        <td class="py-3.5 px-6 font-bold text-slate-900 dark:text-white">{{ $dinas->nama_dinas }}</td>
                        <td class="py-3.5 px-6 max-w-xs">{{ $dinas->alamat ?: '-' }}</td>
                        <td class="py-3.5 px-6">
                            @if ($kurang = $dinas->kopKurang())
                                <span class="inline-block px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300 text-xs font-bold">Belum lengkap</span>
                                <span class="block mt-1 text-xs text-slate-500 dark:text-slate-400">{{ implode(', ', $kurang) }}</span>
                            @else
                                <span class="inline-block px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300 text-xs font-bold">Lengkap</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6">
                            @if($dinas->status)
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300 text-xs font-bold">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300 text-xs font-bold">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.dinas.edit', $dinas->id) }}"
                                   class="inline-flex h-8 items-center px-3 rounded-lg text-xs font-bold transition
                                          bg-blue-50 hover:bg-blue-100 text-blue-700
                                          dark:bg-blue-500/10 dark:hover:bg-blue-500/20 dark:text-blue-300">Edit</a>
                                <x-admin.delete-button :action="route('admin.dinas.destroy', $dinas->id)"
                                    confirm="Yakin ingin menghapus dinas ini? Bidang/pendaftaran yang terhubung mungkin ikut terdampak." />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 px-6 text-center font-semibold text-slate-400 dark:text-slate-500">Belum ada dinas terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(isset($dinasList) && method_exists($dinasList, 'hasPages') && $dinasList->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">{{ $dinasList->links() }}</div>
        @endif
    </x-admin.table-card>

@endsection