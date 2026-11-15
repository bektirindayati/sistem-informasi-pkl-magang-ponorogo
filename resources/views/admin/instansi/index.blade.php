@extends('admin.layout.app')

@section('page-title', 'Kelola Instansi / Bidang')

@push('styles')
<style>
    .select-modern {
        appearance: none;
        -webkit-appearance: none;
        background-repeat: no-repeat;
        background-position: right .7rem center;
        background-size: 1rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        padding-right: 2.25rem !important;
    }
</style>
@endpush

@section('content')

    @php
        $super = Auth::user()->isSuperAdmin();
        $label = 'block mb-1.5 text-sm font-semibold text-slate-800 dark:text-slate-200';
        $field = 'w-full h-10 px-3.5 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950/60
                  text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 dark:placeholder:text-slate-500
                  focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/20 transition';
        $galat = 'mt-1 text-xs font-semibold text-rose-600 dark:text-rose-400';
        $jumlah = (isset($bidangs) && method_exists($bidangs, 'total')) ? $bidangs->total() : count($bidangs ?? []);
    @endphp

    @if(session('success'))
        <x-alert type="success"> {{ session('success') }}</x-alert>
    @endif

    {{-- Form Tambah Bidang --}}
    <section class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm dark:shadow-none overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Tambah Bidang Baru</h3>
            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">Bidang menjadi pilihan tujuan magang pada pendaftaran.</p>
        </div>

        <form action="{{ route('admin.instansi.store') }}" method="POST" class="grid grid-cols-1 gap-4 p-6 md:grid-cols-2">
            @csrf

            {{-- Dropdown dinas hanya untuk super admin; admin dinas otomatis dikunci ke dinasnya sendiri oleh server --}}
            @if($super)
                <div>
                    <label for="dinas_id" class="{{ $label }}">Dinas Pemilik Bidang</label>
                    <select id="dinas_id" name="dinas_id" required
                            class="select-modern {{ $field }} {{ $errors->has('dinas_id') ? '!border-rose-500' : '' }}">
                        <option value="" disabled {{ old('dinas_id') ? '' : 'selected' }}>Pilih dinas</option>
                        @foreach($dinasList as $dinas)
                            <option value="{{ $dinas->id }}" {{ old('dinas_id') == $dinas->id ? 'selected' : '' }}>{{ $dinas->nama_dinas }}</option>
                        @endforeach
                    </select>
                    @error('dinas_id') <p class="{{ $galat }}">{{ $message }}</p> @enderror
                </div>
            @else
                <div class="md:col-span-2 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                    Bidang ini otomatis ditambahkan untuk dinas Anda:
                    <span class="font-bold text-slate-900 dark:text-white">{{ Auth::user()->dinas->nama_dinas ?? '-' }}</span>
                </div>
            @endif

            <div class="{{ $super ? '' : 'md:col-span-2' }}">
                <label for="nama_bidang" class="{{ $label }}">Nama Bidang</label>
                <input id="nama_bidang" type="text" name="nama_bidang" value="{{ old('nama_bidang') }}" required
                       placeholder="Contoh: Bidang E-Government"
                       class="{{ $field }} {{ $errors->has('nama_bidang') ? '!border-rose-500' : '' }}">
                @error('nama_bidang') <p class="{{ $galat }}">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label for="deskripsi" class="{{ $label }}">
                    Deskripsi <span class="font-normal text-slate-400 dark:text-slate-500">(opsional)</span>
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="2" placeholder="Deskripsi singkat bidang"
                          class="{{ str_replace('h-10', 'py-2.5', $field) }}">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="md:col-span-2 flex justify-end">
                <button type="submit"
                        class="h-10 rounded-lg bg-blue-600 px-6 text-sm font-bold text-white transition hover:bg-blue-700 dark:hover:bg-blue-500">
                    Simpan Bidang
                </button>
            </div>
        </form>
    </section>

    {{-- Tabel Daftar Bidang --}}
    <x-admin.table-card title="Daftar Instansi / Bidang Terdaftar" description="Bidang tujuan magang untuk setiap dinas.">
        <x-slot:actions>
            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-200 text-xs font-bold whitespace-nowrap">
                {{ $jumlah }} bidang
            </span>
        </x-slot:actions>

        <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                    <th class="py-3 px-6 w-14">No</th>
                    <th class="py-3 px-6">Dinas</th>
                    <th class="py-3 px-6">Nama Bidang</th>
                    <th class="py-3 px-6">Deskripsi</th>
                    <th class="py-3 px-6 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-300">
                @forelse($bidangs ?? [] as $index => $bidang)
                    <tr class="align-top hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                        <td class="py-3.5 px-6 text-slate-500 dark:text-slate-400">
                            {{ method_exists($bidangs, 'firstItem') ? $bidangs->firstItem() + $index : $index + 1 }}
                        </td>
                        <td class="py-3.5 px-6">
                            @if($bidang->dinas)
                                <span class="inline-block px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300 text-xs font-bold">{{ $bidang->dinas->nama_dinas }}</span>
                            @else
                                <span class="inline-block px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 dark:bg-rose-500/15 dark:text-rose-300 text-xs font-bold">Belum ada dinas</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 font-bold text-slate-900 dark:text-white">{{ $bidang->nama_bidang }}</td>
                        <td class="py-3.5 px-6 max-w-sm">{{ $bidang->deskripsi ?: '-' }}</td>
                        <td class="py-3.5 px-6 text-right">
                            <x-admin.delete-button :action="route('admin.instansi.destroy', $bidang->id)" confirm="Yakin ingin menghapus bidang ini?" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 px-6 text-center font-semibold text-slate-400 dark:text-slate-500">Belum ada bidang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(isset($bidangs) && method_exists($bidangs, 'hasPages') && $bidangs->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">{{ $bidangs->links() }}</div>
        @endif
    </x-admin.table-card>

@endsection