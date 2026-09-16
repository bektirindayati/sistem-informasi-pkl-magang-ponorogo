@extends('admin.layout.app')

@section('page-title', 'Kelola Dinas')

@section('content')

    @if(session('success'))
        <x-alert type="success">✨ {{ session('success') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @endif

    {{-- Form Tambah Dinas --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-5 md:p-6 shadow-sm">
        <h3 class="font-extrabold text-slate-900 text-base mb-4">Tambah Dinas Baru</h3>
        <form action="{{ route('admin.dinas.store') }}" method="POST" class="space-y-4">
            @csrf

            <x-admin.field label="Nama Dinas">
                <input type="text" name="nama_dinas" value="{{ old('nama_dinas') }}" placeholder="Contoh: Dinas Komunikasi, Informasi dan Statistik" required class="w-full px-4 py-3 rounded-xl border {{ $errors->has('nama_dinas') ? 'border-rose-500' : 'border-slate-200' }} text-sm bg-slate-50">
                @error('nama_dinas') <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p> @enderror
            </x-admin.field>

            <x-admin.field label="Alamat (opsional)">
                <input type="text" name="alamat" value="{{ old('alamat') }}" placeholder="Alamat kantor dinas" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50">
            </x-admin.field>

            <x-admin.field label="Deskripsi (opsional)">
                <textarea name="deskripsi" rows="2" placeholder="Deskripsi singkat dinas" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50">{{ old('deskripsi') }}</textarea>
            </x-admin.field>

            {{--
                Checkbox HTML tidak mengirim apa pun kalau tidak dicentang.
                Hidden input di depan checkbox memastikan "status" selalu
                terkirim (0 kalau tidak dicentang, ditimpa 1 kalau dicentang
                karena checkbox datang setelahnya di form data).
            --}}
            <label class="flex items-center gap-2.5 text-sm font-semibold text-slate-700">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                Aktif (tampil di halaman publik & pilihan pendaftaran)
            </label>

            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-sm transition">Simpan Dinas</button>
        </form>
    </div>

    {{-- Tabel Daftar Dinas --}}
    <x-admin.table-card title="Daftar Dinas Terdaftar" title-size="text-base md:text-lg">
        <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold text-xs uppercase tracking-wider">
                    <th class="p-4 px-6">No</th>
                    <th class="p-4 px-6">Nama Dinas</th>
                    <th class="p-4 px-6">Alamat</th>
                    <th class="p-4 px-6">Status</th>
                    <th class="p-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                @forelse($dinasList ?? [] as $index => $dinas)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 px-6 font-bold text-slate-900">
                            {{ method_exists($dinasList, 'firstItem') ? $dinasList->firstItem() + $index : $index + 1 }}
                        </td>
                        <td class="p-4 px-6">{{ $dinas->nama_dinas }}</td>
                        <td class="p-4 px-6 text-slate-500 text-xs">{{ $dinas->alamat ?: '-' }}</td>
                        <td class="p-4 px-6">
                            @if($dinas->status)
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-bold">Nonaktif</span>
                            @endif
                        </td>
                        <td class="p-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.dinas.edit', $dinas->id) }}" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-bold transition">Edit</a>
                                <x-admin.delete-button :action="route('admin.dinas.destroy', $dinas->id)" confirm="Yakin ingin menghapus dinas ini? Bidang/pendaftaran yang terhubung mungkin ikut terdampak." />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-400 font-bold">Belum ada dinas terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>

    @if(isset($dinasList) && method_exists($dinasList, 'links'))
        <div>{{ $dinasList->links() }}</div>
    @endif

@endsection