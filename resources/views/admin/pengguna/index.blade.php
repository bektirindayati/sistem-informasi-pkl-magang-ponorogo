@extends('admin.layout.app')

@section('page-title', 'Kelola Admin & Pengguna')

@section('content')

    @php
        // Cadangan bila controller belum diubah: pisahkan dari $users (lihat bagian 3)
        if (!isset($superAdmins)) {
            $semua = $users instanceof \Illuminate\Pagination\AbstractPaginator
                ? $users->getCollection()
                : collect($users);

            $superAdmins = $semua->where('role', 'super_admin')->values();
            $adminDinas  = $semua->where('role', 'admin')->values();
            $pengguna    = $semua->filter(fn ($u) => !in_array($u->role, ['super_admin', 'admin']))->values();
        }

        // Pagination hanya untuk tabel pengguna (paling bawah)
        $halaman = method_exists($pengguna, 'links')
            ? $pengguna
            : ((isset($users) && method_exists($users, 'links')) ? $users : null);
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


    {{-- PENCARIAN (berlaku untuk ketiga tabel) --}}
    <form method="GET"
          class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800
                 p-4 shadow-sm dark:shadow-none flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama atau email..."
               class="flex-1 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-800
                      bg-white dark:bg-slate-950/60 text-sm text-slate-700 dark:text-slate-100
                      placeholder:text-slate-400 dark:placeholder:text-slate-500
                      focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/20">

        <button type="submit"
                class="px-5 py-2 bg-slate-800 hover:bg-slate-900 dark:bg-blue-600 dark:hover:bg-blue-500
                       text-white text-sm font-bold rounded-xl transition">
            Cari
        </button>

        @if(request('search'))
            <a href="{{ route('admin.pengguna.index') }}"
               class="px-4 py-2 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white
                      text-sm font-bold transition">
                Reset
            </a>
        @endif
    </form>


    {{-- 1. SUPER ADMIN — selalu paling atas --}}
    <x-admin.tabel-pengguna
        :users="$superAdmins"
        :dinas-list="$dinasList"
        icon="👑"
        title="Super Admin"
        description="Akun dengan akses penuh ke seluruh dinas dan pengaturan sistem."
        empty="Tidak ada Super Admin yang cocok."
    />

    {{-- 2. ADMIN DINAS --}}
    <x-admin.tabel-pengguna
        :users="$adminDinas"
        :dinas-list="$dinasList"
        :show-dinas="true"
        icon="🏛️"
        title="Admin Dinas"
        description="Akun pengelola yang terkunci pada satu dinas."
        empty="Belum ada Admin Dinas."
    />

    {{-- 3. USER / PENDAFTAR --}}
<x-admin.tabel-pengguna
    :users="$pengguna"
    :dinas-list="$dinasList"
    icon="👥"
    title="User / Pendaftar"
    description="Akun pengguna biasa yang mendaftar magang atau PKL."
    empty="Belum ada pengguna."
    :show-account-status="true"
/>

@if($halaman)
    <div>{{ $halaman->links() }}</div>
@endif

@endsection

@push('scripts')
<script>
    // Tampilkan pilihan Dinas cuma kalau role yang dipilih itu 'admin'
    function toggleDinasSelect(select) {
        const form = select.closest('form');
        const dinasSelect = form.querySelector('.dinas-select');
        if (select.value === 'admin') {
            dinasSelect.classList.remove('hidden');
        } else {
            dinasSelect.classList.add('hidden');
        }
    }
</script>
@endpush