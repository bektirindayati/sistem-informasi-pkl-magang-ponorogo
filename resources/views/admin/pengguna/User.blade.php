@extends('admin.layout.app')

@section('page-title', 'Kelola Pengguna')

@push('styles')
<style>
    /* Dropdown seragam: panah kustom, tinggi tetap */
    .select-modern {
        appearance: none;
        -webkit-appearance: none;
        background-repeat: no-repeat;
        background-position: right .6rem center;
        background-size: 1rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        padding-right: 2rem !important;
    }
</style>
@endpush

@section('content')

    @php
        $selectCls = 'select-modern h-9 rounded-lg border border-slate-200 dark:border-slate-700
                      bg-white dark:bg-slate-950/60 pl-3 text-xs font-semibold
                      text-slate-800 dark:text-slate-100
                      focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/20';

        $btnCls = 'h-9 px-4 bg-slate-800 hover:bg-slate-900 dark:bg-blue-600 dark:hover:bg-blue-500
                   text-white text-xs font-bold rounded-lg transition';
    @endphp

    @if(session('success'))
        <x-alert type="success"> {{ session('success') }}</x-alert>
    @endif
    @if ($errors->any())
        <x-alert type="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </x-alert>
    @endif

    <section class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800
                    shadow-sm dark:shadow-none overflow-hidden">

        {{-- HEADER CARD: judul + pencarian --}}
        <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800
                    flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">User / Pendaftar</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-200
                                 text-xs font-bold whitespace-nowrap">
                        {{ $pengguna->total() }} akun
                    </span>
                </div>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Akun pengguna biasa yang mendaftar magang atau PKL.
                </p>
            </div>

            <form method="GET" action="{{ route('admin.pengguna.user') }}" class="flex items-center gap-2 lg:w-[420px]">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 10.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama atau email pendaftar..."
                           class="w-full h-10 pl-9 pr-3 rounded-xl border border-slate-200 dark:border-slate-800
                                  bg-white dark:bg-slate-950/60 text-sm text-slate-700 dark:text-slate-100
                                  placeholder:text-slate-400 dark:placeholder:text-slate-500
                                  focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/20">
                </div>

                <button type="submit"
                        class="h-10 px-5 bg-slate-800 hover:bg-slate-900 dark:bg-blue-600 dark:hover:bg-blue-500
                               text-white text-sm font-bold rounded-xl transition">
                    Cari
                </button>

                @if(request('search'))
                    <a href="{{ route('admin.pengguna.user') }}"
                       class="h-10 inline-flex items-center px-2 text-slate-500 hover:text-slate-800
                              dark:text-slate-400 dark:hover:text-white text-sm font-bold transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- TABEL --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800
                               text-slate-600 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                        <th class="py-3 px-6 w-[26%]">Pengguna</th>
                        <th class="py-3 px-6 w-[12%]">Status</th>
                        <th class="py-3 px-6 w-[27%]">Ubah Status Akun</th>
                        <th class="py-3 px-6">Ubah Role</th>
                    </tr>
                </thead>

                <tbody class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    @forelse($pengguna as $u)
                        @php $statusAkun = $u->status_akun ?? 'aktif'; @endphp

                        <tr class="border-b border-slate-100 dark:border-slate-800 last:border-b-0 align-top
                                   hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">

                            {{-- Pengguna --}}
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900 dark:text-white">{{ $u->name }}</div>
                                <div class="mt-0.5 text-xs text-slate-500 dark:text-slate-400 break-all">{{ $u->email }}</div>
                            </td>

                            {{-- Status saat ini --}}
                            <td class="py-4 px-6">
                                @if($statusAkun === 'diblokir')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full
                                                 bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-300 text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span> Diblokir
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full
                                                 bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300 text-xs font-bold">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span> Aktif
                                    </span>
                                @endif
                            </td>

                            {{-- Ubah status akun --}}
                            <td class="py-4 px-6">
                                <form action="{{ route('admin.pengguna.status-akun', $u->id) }}"
                                      method="POST"
                                      onsubmit="return konfirmasiStatusAkun(this)"
                                      class="flex flex-col gap-2 max-w-[260px]">
                                    @csrf
                                    @method('PATCH')

                                    <div class="flex items-center gap-2">
                                        <select name="status_akun" onchange="toggleAlasanBlokir(this)" class="flex-1 {{ $selectCls }}">
                                            <option value="aktif" {{ $statusAkun === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="diblokir" {{ $statusAkun === 'diblokir' ? 'selected' : '' }}>Diblokir</option>
                                        </select>
                                        <button type="submit" class="{{ $btnCls }}">Simpan</button>
                                    </div>

                                    {{-- Alasan hanya tampil saat memilih Diblokir --}}
                                    <div class="alasan-blokir {{ $statusAkun === 'diblokir' ? '' : 'hidden' }}">
                                        <textarea name="alasan_blokir" rows="2" maxlength="1000"
                                                  placeholder="Tuliskan alasan pemblokiran..."
                                                  class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700
                                                         bg-white dark:bg-slate-950/60 text-xs text-slate-800 dark:text-slate-100
                                                         focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/20"
                                                  {{ $statusAkun === 'diblokir' ? 'required' : '' }}>{{ $statusAkun === 'diblokir' ? $u->alasan_blokir : '' }}</textarea>
                                    </div>
                                </form>
                            </td>

                            {{-- Ubah role: hanya User Biasa / Admin Dinas --}}
                            <td class="py-4 px-6">
                                <form action="{{ route('admin.pengguna.update', $u->id) }}"
                                      method="POST"
                                      class="flex flex-wrap items-center gap-2">
                                    @csrf
                                    @method('PUT')

                                    <select name="role" onchange="toggleDinasSelect(this)" class="w-36 {{ $selectCls }}">
                                        <option value="user" selected>User Biasa</option>
                                        <option value="admin">Admin Dinas</option>
                                    </select>

                                    <select name="dinas_id" class="dinas-select hidden w-60 max-w-full truncate {{ $selectCls }}">
                                        <option value="">Pilih dinas</option>
                                        @foreach($dinasList as $dinas)
                                            <option value="{{ $dinas->id }}">{{ $dinas->nama_dinas }}</option>
                                        @endforeach
                                    </select>

                                    <button type="submit" class="{{ $btnCls }}">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 px-6 text-center text-slate-400 dark:text-slate-300 font-semibold">
                                {{ request('search') ? 'Tidak ada pengguna yang cocok dengan pencarian.' : 'Belum ada pengguna.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($pengguna->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                {{ $pengguna->links() }}
            </div>
        @endif
    </section>

@endsection

@push('scripts')
<script>
    // Pilihan dinas hanya tampil & wajib diisi bila role = Admin Dinas
    function toggleDinasSelect(select) {
        const dinas = select.closest('form').querySelector('.dinas-select');
        const perlu = select.value === 'admin';
        dinas.classList.toggle('hidden', !perlu);
        dinas.required = perlu;
    }

    // Kolom alasan hanya muncul & wajib saat status Diblokir
    function toggleAlasanBlokir(select) {
        const alasan = select.closest('form').querySelector('.alasan-blokir');
        const textarea = alasan.querySelector('textarea');
        const blokir = select.value === 'diblokir';

        alasan.classList.toggle('hidden', !blokir);
        textarea.required = blokir;
    }

    function konfirmasiStatusAkun(form) {
        const status = form.querySelector('[name="status_akun"]').value;
        const alasan = form.querySelector('[name="alasan_blokir"]');

        if (status === 'diblokir' && !alasan.value.trim()) {
            alert('Alasan pemblokiran wajib diisi.');
            alasan.focus();
            return false;
        }

        if (status === 'diblokir') {
            return confirm(
                'Apakah Anda yakin ingin memblokir akun ini?\n\n' +
                'Pengguna tetap dapat login dan melihat riwayat, ' +
                'tetapi tidak dapat membuat atau mengubah pengajuan.'
            );
        }

        return confirm('Apakah Anda yakin ingin membuka blokir akun ini?');
    }
</script>
@endpush