@extends('admin.layout.app')

@section('page-title', 'Kelola Admin & Pengguna')

@section('content')

    @if(session('success'))
        <x-alert type="success">✨ {{ session('success') }}</x-alert>
    @endif
    @if ($errors->any())
        <x-alert type="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </x-alert>
    @endif


    <form method="GET" class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="flex-1 px-4 py-2 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600">
        <button type="submit" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-xl transition">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.pengguna.index') }}" class="px-4 py-2 text-slate-500 hover:text-slate-800 text-sm font-bold transition">Reset</a>
        @endif
    </form>

    <x-admin.table-card title="Daftar Pengguna">
        <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold text-xs uppercase tracking-wider">
                    <th class="p-4 px-6">Nama</th>
                    <th class="p-4 px-6">Email</th>
                    <th class="p-4 px-6">Role Sekarang</th>
                    <th class="p-4 px-6">Ubah Role & Dinas</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                @forelse($users as $u)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 px-6 font-bold text-slate-900">{{ $u->name }}</td>
                        <td class="p-4 px-6 text-slate-500 text-xs">{{ $u->email }}</td>
                        <td class="p-4 px-6">
                            @php
                                $roleBadge = [
                                    'super_admin' => 'bg-purple-100 text-purple-700',
                                    'admin' => 'bg-blue-100 text-blue-700',
                                ][$u->role] ?? 'bg-slate-100 text-slate-600';
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $roleBadge }}">
                                {{ ucfirst(str_replace('_', ' ', $u->role ?? 'user')) }}
                            </span>
                            @if($u->dinas)
                                <span class="block text-[11px] text-slate-400 mt-1">{{ $u->dinas->nama_dinas }}</span>
                            @endif
                        </td>
                        <td class="p-4 px-6">
                            <form action="{{ route('admin.pengguna.update', $u->id) }}" method="POST" class="flex flex-col sm:flex-row gap-2">
                                @csrf
                                @method('PUT')
                                <select name="role" onchange="toggleDinasSelect(this)" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold">
                                    <option value="user" {{ $u->role == 'user' || !$u->role ? 'selected' : '' }}>User Biasa</option>
                                    <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>Admin Dinas</option>
                                    <option value="super_admin" {{ $u->role == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                </select>
                                <select name="dinas_id" class="dinas-select px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold {{ $u->role != 'admin' ? 'hidden' : '' }}">
                                    <option value="">-- Pilih Dinas --</option>
                                    @foreach($dinasList as $dinas)
                                        <option value="{{ $dinas->id }}" {{ $u->dinas_id == $dinas->id ? 'selected' : '' }}>{{ $dinas->nama_dinas }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-lg transition">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-8 text-center text-slate-400 font-bold">Belum ada pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>

    @if(method_exists($users, 'links'))
        <div>{{ $users->links() }}</div>
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