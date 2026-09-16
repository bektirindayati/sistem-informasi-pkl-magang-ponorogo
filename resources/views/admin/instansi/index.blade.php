@extends('admin.layout.app')

@section('page-title', 'Kelola Instansi / Bidang')

@section('content')

  {{-- <h2>Daftar Pengaju Magang & PKL</h2>--}}
    <p class="text-sm text-slate-500 font-semibold">Kelola dan pantau seluruh data mahasiswa atau siswa yang mendaftar ke instansi.</p>
    @if(session('success'))
        <x-alert type="success">✨ {{ session('success') }}</x-alert>
    @endif

    {{-- Form Tambah Bidang --}}
    <div class="bg-white rounded-3xl border border-slate-200 p-5 md:p-6 shadow-sm">
        <h3 class="font-extrabold text-slate-900 text-base mb-4">Tambah Bidang Baru</h3>
        <form action="{{ route('admin.instansi.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            {{--
                FIX: sebelumnya dropdown ini SELALU ditampilkan dan wajib
                diisi (required), (karena dinas_idsudah otomatis dikunci ke dinasnya sendiri di server).(tidak
                perlu pilih apa-apa, dinas_id-nya sudah pasti dari server).
            --}}
            @if(Auth::user()->isSuperAdmin())
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Dinas Pemilik Bidang</label>
                    <select name="dinas_id" required class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('dinas_id') ? 'border-rose-500' : 'border-slate-200' }} text-sm font-medium focus:outline-none focus:border-blue-600">
                        <option value="" disabled {{ old('dinas_id') ? '' : 'selected' }}>-- Pilih Dinas --</option>
                        @foreach($dinasList as $dinas)
                            <option value="{{ $dinas->id }}" {{ old('dinas_id') == $dinas->id ? 'selected' : '' }}>{{ $dinas->nama_dinas }}</option>
                        @endforeach
                    </select>
                    @error('dinas_id') <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p> @enderror
                </div>
            @else
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-600">
                    Bidang ini akan otomatis ditambahkan untuk dinas kamu:
                    <span class="font-bold text-slate-800">{{ Auth::user()->dinas->nama_dinas ?? '-' }}</span>
                </div>
            @endif

            <div class="flex flex-col sm:flex-row gap-4">
                <input type="text" name="nama_bidang" value="{{ old('nama_bidang') }}" placeholder="Nama Bidang (Contoh: Bidang E-Government)" required class="flex-1 px-4 py-2.5 rounded-xl border {{ $errors->has('nama_bidang') ? 'border-rose-500' : 'border-slate-200' }} text-sm focus:outline-none focus:border-blue-600 font-medium">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-sm transition shrink-0 sm:self-start">Simpan Bidang</button>
            </div>
            @error('nama_bidang') <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror

            <textarea name="deskripsi" rows="2" placeholder="Deskripsi singkat (opsional)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-600 font-medium">{{ old('deskripsi') }}</textarea>
        </form>
    </div>

    {{-- Tabel Daftar Bidang --}}
    <x-admin.table-card title="Daftar Instansi / Bidang Terdaftar" title-size="text-base md:text-lg">
        <table class="w-full text-left border-collapse min-w-[700px]">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold text-xs uppercase tracking-wider">
                    <th class="p-4 px-6">No</th>
                    <th class="p-4 px-6">Dinas</th>
                    <th class="p-4 px-6">Nama Bidang</th>
                    <th class="p-4 px-6">Deskripsi</th>
                    <th class="p-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                @forelse($bidangs ?? [] as $index => $bidang)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 px-6 font-bold text-slate-900">
                            {{ method_exists($bidangs, 'firstItem') ? $bidangs->firstItem() + $index : $index + 1 }}
                        </td>
                        <td class="p-4 px-6">
                            @if($bidang->dinas)
                                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold">{{ $bidang->dinas->nama_dinas }}</span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 text-xs font-bold">⚠️ Belum ada dinas</span>
                            @endif
                        </td>
                        <td class="p-4 px-6">{{ $bidang->nama_bidang }}</td>
                        <td class="p-4 px-6 text-slate-500 text-xs">{{ $bidang->deskripsi ?: '-' }}</td>
                        <td class="p-4 px-6 text-center">
                            <x-admin.delete-button :action="route('admin.instansi.destroy', $bidang->id)" confirm="Yakin ingin menghapus bidang ini?" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-400 font-bold">Belum ada bidang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>

    @if(isset($bidangs) && method_exists($bidangs, 'links'))
        <div>{{ $bidangs->links() }}</div>
    @endif

@endsection