@extends('admin.layout.app')

@section('page-title', 'Kelola Admin')

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

        $badgeRole = [
            'super_admin' => 'bg-purple-100 text-purple-700 dark:bg-purple-500/15 dark:text-purple-300',
            'admin'       => 'bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-300',
        ];

        $kelompok = [
            ['label' => 'Super Admin', 'items' => $superAdmins],
            ['label' => 'Admin Dinas', 'items' => $adminDinas],
        ];

        $total = $superAdmins->count() + $adminDinas->count();
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
                    <h2 class="text-lg font-extrabold text-slate-900 dark:text-white">Admin &amp; Super Admin</h2>
                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-200
                                 text-xs font-bold whitespace-nowrap">
                        {{ $total }} akun
                    </span>
                </div>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Kelola akun pengelola sistem. Super Admin memiliki akses penuh, Admin Dinas terkunci pada satu dinas.
                </p>
            </div>

            <form method="GET" action="{{ route('admin.pengguna.index') }}" class="flex items-center gap-2 lg:w-[420px]">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 10.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama atau email admin..."
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
                    <a href="{{ route('admin.pengguna.index') }}"
                       class="h-10 inline-flex items-center px-2 text-slate-500 hover:text-slate-800
                              dark:text-slate-400 dark:hover:text-white text-sm font-bold transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- TABEL --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800
                               text-slate-600 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                        <th class="py-3 px-6 w-[28%]">Pengguna</th>
                        <th class="py-3 px-6 w-[12%]">Role</th>
                        <th class="py-3 px-6 w-[22%]">Dinas</th>
                        <th class="py-3 px-6">Ubah Role &amp; Dinas</th>
                    </tr>
                </thead>

                <tbody class="text-sm font-medium text-slate-700 dark:text-slate-300">
                    @foreach($kelompok as $grup)
                        @continue($grup['items']->isEmpty())

                        {{-- Penanda kelompok --}}
                        <tr class="bg-slate-50/70 dark:bg-slate-950/40 border-y border-slate-100 dark:border-slate-800">
                            <td colspan="4" class="py-2 px-6 text-xs font-bold text-slate-500 dark:text-slate-400">
                                {{ $grup['label'] }} ({{ $grup['items']->count() }})
                            </td>
                        </tr>

                        @foreach($grup['items'] as $u)
                            <tr class="border-b border-slate-100 dark:border-slate-800 last:border-b-0
                                       hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">

                                {{-- Pengguna --}}
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900 dark:text-white">
                                        {{ $u->name }}
                                        @if(auth()->id() === $u->id)
                                            <span class="ml-1.5 px-1.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700
                                                         dark:bg-emerald-500/15 dark:text-emerald-300 text-[10px] font-bold align-middle">
                                                Anda
                                            </span>
                                        @endif
                                    </div>
                                    <div class="mt-0.5 text-xs text-slate-500 dark:text-slate-400 break-all">{{ $u->email }}</div>
                                </td>

                                {{-- Role --}}
                                <td class="py-4 px-6">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold whitespace-nowrap {{ $badgeRole[$u->role] ?? '' }}">
                                        {{ $u->role === 'super_admin' ? 'Super Admin' : 'Admin Dinas' }}
                                    </span>
                                </td>

                                {{-- Dinas --}}
                                <td class="py-4 px-6 text-xs text-slate-600 dark:text-slate-300">
                                    @if($u->role === 'super_admin')
                                        <span class="text-slate-400 dark:text-slate-500">Semua dinas</span>
                                    @else
                                        {{ $u->dinas->nama_dinas ?? '—' }}
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="py-4 px-6">
                                    @if($u->role === 'super_admin')
                                        {{-- Super Admin: permanen, tanpa dropdown & tombol simpan --}}
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 dark:text-slate-500">
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.5a4.5 4.5 0 0 0-9 0v3m-.75 0h10.5A1.25 1.25 0 0 1 18.5 11.75v7A1.25 1.25 0 0 1 17.25 20H6.75a1.25 1.25 0 0 1-1.25-1.25v-7A1.25 1.25 0 0 1 6.75 10.5Z"/>
                                            </svg>
                                            Permanen, tidak dapat diubah
                                        </span>
                                    @else
                                        <form action="{{ route('admin.pengguna.update', $u->id) }}"
                                              method="POST"
                                              onsubmit="return konfirmasiRole(this)"
                                              class="flex flex-wrap items-center gap-2">
                                            @csrf
                                            @method('PUT')

                                            <select name="role" onchange="toggleDinasSelect(this)" class="w-36 {{ $selectCls }}">
                                                <option value="admin" selected>Admin Dinas</option>
                                                <option value="super_admin">Super Admin</option>
                                            </select>

                                            <select name="dinas_id"
                                                    class="dinas-select w-60 max-w-full truncate {{ $selectCls }}"
                                                    required>
                                                <option value="">Pilih dinas</option>
                                                @foreach($dinasList as $dinas)
                                                    <option value="{{ $dinas->id }}" {{ $u->dinas_id == $dinas->id ? 'selected' : '' }}>
                                                        {{ $dinas->nama_dinas }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <button type="submit" class="{{ $btnCls }}">Simpan</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach

                    @if($total === 0)
                        <tr>
                            <td colspan="4" class="py-12 px-6 text-center text-slate-400 dark:text-slate-300 font-semibold">
                                {{ request('search') ? 'Tidak ada admin yang cocok dengan pencarian.' : 'Belum ada admin.' }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
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

    // Menjadikan Super Admin bersifat permanen, jadi minta konfirmasi
    function konfirmasiRole(form) {
        const role = form.querySelector('[name="role"]').value;
        if (role === 'super_admin') {
            return confirm(
                'Jadikan akun ini Super Admin?\n\n' +
                'Role Super Admin bersifat permanen dan tidak dapat diubah kembali dari halaman ini.'
            );
        }
        return true;
    }
</script>
@endpush