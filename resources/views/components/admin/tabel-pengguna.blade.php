
{{-- resources/views/components/admin/tabel-pengguna.blade.php --}}
@props([
    'users',
    'dinasList'         => [],
    'title'             => '',
    'icon'              => null,
    'description'       => null,
    'empty'             => 'Belum ada data.',
    'showDinas'         => false,
    'showAccountStatus' => false,
])

@php
    $jumlah = method_exists($users, 'total') ? $users->total() : count($users);
    $kolom  = 4 + ($showDinas ? 1 : 0) + ($showAccountStatus ? 1 : 0);

    $selectCls = 'px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700
                  bg-white dark:bg-slate-950/60 text-xs font-semibold
                  text-slate-800 dark:text-slate-100
                  focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/20';

    $badgeRole = [
        'super_admin' => 'bg-purple-100 text-purple-700 dark:bg-purple-500/15 dark:text-purple-300',
        'admin'       => 'bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-300',
    ];
@endphp

<x-admin.table-card :title="$title" :icon="$icon" :description="$description">

    <x-slot name="actions">
        <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-200 text-xs font-bold whitespace-nowrap">
            {{ $jumlah }} akun
        </span>
    </x-slot>

    <table class="w-full text-left border-collapse min-w-[800px]">
        <thead>
            <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800
                       text-slate-600 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                <th class="p-4 px-6">Nama</th>
                <th class="p-4 px-6">Email</th>
                <th class="p-4 px-6">Role Sekarang</th>

                @if($showDinas)
                    <th class="p-4 px-6">Dinas</th>
                @endif

                @if($showAccountStatus)
                    <th class="p-4 px-6">Status Akun</th>
                @endif

                <th class="p-4 px-6">Ubah Role &amp; Dinas</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm font-medium text-slate-700 dark:text-slate-300">
            @forelse($users as $u)
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">

                    {{-- Nama --}}
                    <td class="p-4 px-6 font-bold text-slate-900 dark:text-white">
                        {{ $u->name }}

                        @if(auth()->id() === $u->id)
                            <span class="ml-1.5 px-1.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300 text-[10px] font-bold align-middle">
                                Anda
                            </span>
                        @endif
                    </td>

                    {{-- Email --}}
                    <td class="p-4 px-6 text-slate-500 dark:text-slate-300 text-xs">
                        {{ $u->email }}
                    </td>

                    {{-- Role --}}
                    <td class="p-4 px-6">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold
                                     {{ $badgeRole[$u->role] ?? 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-200' }}">
                            {{ ucfirst(str_replace('_', ' ', $u->role ?? 'user')) }}
                        </span>
                    </td>

                    {{-- Dinas --}}
                    @if($showDinas)
                        <td class="p-4 px-6 text-xs text-slate-600 dark:text-slate-200">
                            {{ $u->dinas->nama_dinas ?? '—' }}
                        </td>
                    @endif

                    {{-- Status Akun --}}
                    @if($showAccountStatus)
                        <td class="p-4 px-6">
                            @if(in_array($u->role, ['user', null], true))

                                @php
                                    $statusAkun = $u->status_akun ?? 'aktif';
                                @endphp

                                <div class="flex flex-col gap-2 min-w-[190px]">

                                    {{-- Badge status saat ini --}}
                                    @if($statusAkun === 'diblokir')
                                        <span class="inline-flex w-fit px-2.5 py-1 rounded-full
                                                     bg-red-100 text-red-700
                                                     dark:bg-red-500/15 dark:text-red-300
                                                     text-xs font-bold">
                                            <span class="mr-1.5">●</span> Diblokir
                                        </span>
                                    @else
                                        <span class="inline-flex w-fit px-2.5 py-1 rounded-full
                                                     bg-emerald-100 text-emerald-700
                                                     dark:bg-emerald-500/15 dark:text-emerald-300
                                                     text-xs font-bold">
                                            <span class="mr-1.5">●</span> Aktif
                                        </span>
                                    @endif

                                    {{-- Form ubah status akun --}}
                                    <form action="{{ route('admin.pengguna.status-akun', $u->id) }}"
                                          method="POST"
                                          onsubmit="return konfirmasiStatusAkun(this)"
                                          class="flex flex-col gap-2">

                                        @csrf
                                        @method('PATCH')

                                        <select name="status_akun"
                                                class="{{ $selectCls }}"
                                                onchange="toggleAlasanBlokir(this)">

                                            <option value="aktif"
                                                {{ $statusAkun === 'aktif' ? 'selected' : '' }}>
                                                Aktif
                                            </option>

                                            <option value="diblokir"
                                                {{ $statusAkun === 'diblokir' ? 'selected' : '' }}>
                                                Diblokir
                                            </option>
                                        </select>

                                        {{-- Alasan hanya ditampilkan saat memilih Diblokir --}}
                                        <div class="alasan-blokir {{ $statusAkun === 'diblokir' ? '' : 'hidden' }}">
                                            <textarea name="alasan_blokir"
                                                      rows="2"
                                                      maxlength="1000"
                                                      placeholder="Tuliskan alasan pemblokiran..."
                                                      class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700
                                                             bg-white dark:bg-slate-950/60 text-xs text-slate-800 dark:text-slate-100
                                                             focus:outline-none focus:border-blue-600"
                                                      {{ $statusAkun === 'diblokir' ? 'required' : '' }}>{{ $statusAkun === 'diblokir' ? $u->alasan_blokir : '' }}</textarea>
                                        </div>

                                        <button type="submit"
                                                class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900
                                                       dark:bg-blue-600 dark:hover:bg-blue-500
                                                       text-white text-xs font-bold rounded-lg transition">
                                            Simpan Status
                                        </button>
                                    </form>
                                </div>

                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                    @endif

                    {{-- Ubah Role & Dinas --}}
                    <td class="p-4 px-6">
                        <form action="{{ route('admin.pengguna.update', $u->id) }}"
                              method="POST"
                              class="flex flex-col sm:flex-row gap-2">

                            @csrf
                            @method('PUT')

                            <select name="role"
                                    onchange="toggleDinasSelect(this)"
                                    class="{{ $selectCls }}">

                                {{-- SUPER ADMIN: hanya bisa tetap Super Admin --}}
                                @if($u->role === 'super_admin')
                                    <option value="super_admin" selected>
                                        Super Admin
                                    </option>

                                {{-- ADMIN DINAS: hanya Admin Dinas atau User --}}
                                @elseif($u->role === 'admin')
                                    <option value="admin" selected>
                                        Admin Dinas
                                    </option>
                                    <option value="user">
                                        User Biasa
                                    </option>

                                {{-- USER: pilihan tetap seperti sebelumnya --}}
                                @else
                                    <option value="user" {{ !$u->role || $u->role === 'user' ? 'selected' : '' }}>
                                        User Biasa
                                    </option>
                                    <option value="admin">
                                        Admin Dinas
                                    </option>
                                    <option value="super_admin">
                                        Super Admin
                                    </option>
                                @endif

                            </select>

                            <select name="dinas_id"
                                    class="dinas-select {{ $u->role !== 'admin' ? 'hidden' : '' }} {{ $selectCls }}">

                                <option value="">-- Pilih Dinas --</option>

                                @foreach($dinasList as $dinas)
                                    <option value="{{ $dinas->id }}"
                                            {{ $u->dinas_id == $dinas->id ? 'selected' : '' }}>
                                        {{ $dinas->nama_dinas }}
                                    </option>
                                @endforeach
                            </select>

                            <button type="submit"
                                    class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 dark:bg-blue-600 dark:hover:bg-blue-500
                                           text-white text-xs font-bold rounded-lg transition">
                                Simpan
                            </button>
                        </form>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="{{ $kolom }}"
                        class="p-8 text-center text-slate-400 dark:text-slate-300 font-bold">
                        {{ $empty }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</x-admin.table-card>

{{-- JavaScript status akun --}}
@if($showAccountStatus)
    @once
        <script>
            function toggleAlasanBlokir(select) {
                const form = select.closest('form');
                const alasan = form.querySelector('.alasan-blokir');
                const textarea = alasan.querySelector('textarea');

                if (select.value === 'diblokir') {
                    alasan.classList.remove('hidden');
                    textarea.required = true;
                } else {
                    alasan.classList.add('hidden');
                    textarea.required = false;
                }
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
    @endonce
@endif
