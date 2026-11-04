@extends('admin.layout.app')

@section('page-title', 'Kelola Dinas')

@section('content')

    @php
        $field = 'w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 text-sm
                  bg-slate-50 dark:bg-slate-950/60 text-slate-800 dark:text-slate-100
                  placeholder:text-slate-400 dark:placeholder:text-slate-500
                  focus:bg-white dark:focus:bg-slate-950 focus:outline-none focus:border-blue-500
                  focus:ring-2 focus:ring-blue-100 dark:focus:ring-blue-500/20 transition';
        $galat = 'text-xs text-rose-600 dark:text-rose-400 font-semibold mt-1';
    @endphp

    @if(session('success'))
        <x-alert type="success"> {{ session('success') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="error">{{ session('error') }}</x-alert>
    @endif

    {{-- Form Tambah Dinas --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-5 md:p-6 shadow-sm dark:shadow-none">
        <h3 class="font-extrabold text-slate-900 dark:text-slate-100 text-base mb-2">Tambah Dinas Baru</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-5">
            Data kop surat (alamat, telepon, faksimile, website, email) digunakan untuk mengisi draf surat balasan magang.
        </p>

        <form action="{{ route('admin.dinas.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.field label="Nama Dinas">
                    <input type="text" name="nama_dinas" value="{{ old('nama_dinas') }}"
                           placeholder="Nama lengkap dinas" required
                           class="{{ $field }} {{ $errors->has('nama_dinas') ? '!border-rose-500' : '' }}">
                    @error('nama_dinas') <p class="{{ $galat }}">{{ $message }}</p> @enderror
                </x-admin.field>

                <x-admin.field label="Alamat Kantor (opsional)">
                    <input type="text" name="alamat" value="{{ old('alamat') }}"
                           placeholder="Alamat lengkap kantor (tampil di kop surat)"
                           class="{{ $field }}">
                    @error('alamat') <p class="{{ $galat }}">{{ $message }}</p> @enderror
                </x-admin.field>

                <x-admin.field label="Telepon Kantor (opsional)">
                    <input type="text" name="telepon" value="{{ old('telepon') }}"
                           placeholder="Nomor telepon kantor"
                           class="{{ $field }}">
                    @error('telepon') <p class="{{ $galat }}">{{ $message }}</p> @enderror
                </x-admin.field>

                <x-admin.field label="Email Dinas (opsional)">
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="email@ponorogo.go.id"
                           class="{{ $field }}">
                    @error('email') <p class="{{ $galat }}">{{ $message }}</p> @enderror
                </x-admin.field>

                <x-admin.field label="Website Dinas (opsional)">
                    <input type="text" name="website" value="{{ old('website') }}"
                           placeholder="kominfo.ponorogo.go.id"
                           class="{{ $field }}">
                    @error('website') <p class="{{ $galat }}">{{ $message }}</p> @enderror
                </x-admin.field>

            </div>

            <x-admin.field label="Deskripsi Dinas (opsional)">
                <textarea name="deskripsi" rows="2" placeholder="Deskripsi singkat dinas"
                          class="{{ $field }}">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="{{ $galat }}">{{ $message }}</p> @enderror
            </x-admin.field>

            <div class="border-t border-slate-200 dark:border-slate-800 pt-5">
                <h4 class="font-bold text-slate-900 dark:text-slate-100 mb-1">Pejabat Penandatangan</h4>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">
    Data pejabat digunakan sebagai nilai awal identitas penandatangan
    pada draf surat balasan. Data akan dimasukkan secara otomatis
    ke dalam template surat dan masih dapat disesuaikan.
</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-admin.field label="Nama Pejabat (opsional)">
                        <input type="text" name="nama_pejabat" value="{{ old('nama_pejabat') }}"
                               placeholder="Nama lengkap beserta gelar"
                               class="{{ $field }}">
                        @error('nama_pejabat') <p class="{{ $galat }}">{{ $message }}</p> @enderror
                    </x-admin.field>

                    <x-admin.field label="Jabatan Pejabat (opsional)">
                        <input type="text" name="jabatan_pejabat" value="{{ old('jabatan_pejabat') }}"
                               placeholder="Contoh: Kepala Dinas Komunikasi Informatika dan Statistik"
                               class="{{ $field }}">
                        @error('jabatan_pejabat') <p class="{{ $galat }}">{{ $message }}</p> @enderror
                    </x-admin.field>

                    
                    <x-admin.field label="Pangkat (opsional)">
                        <input type="text" name="pangkat_pejabat"
                            value="{{ old('pangkat_pejabat') }}"
                            placeholder="Contoh: Pembina Utama Muda"
                            class="{{ $field }}">
                        @error('pangkat_pejabat')
                            <p class="{{ $galat }}">{{ $message }}</p>
                        @enderror
                    </x-admin.field>

                    <x-admin.field label="Golongan (opsional)">
                        <input type="text" name="golongan_pejabat"
                            value="{{ old('golongan_pejabat') }}"
                            placeholder="Contoh: IV/c"
                            class="{{ $field }}">
                        @error('golongan_pejabat')
                            <p class="{{ $galat }}">{{ $message }}</p>
                        @enderror
                    </x-admin.field>


                    <x-admin.field label="NIP Pejabat (opsional)">
                        <input type="text" name="nip_pejabat" value="{{ old('nip_pejabat') }}"
                               placeholder="NIP pejabat"
                               class="{{ $field }}">
                        @error('nip_pejabat') <p class="{{ $galat }}">{{ $message }}</p> @enderror
                    </x-admin.field>
                </div>
            </div>

            <label class="flex items-center gap-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" name="status" value="1"
                       {{ old('status', true) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 dark:bg-slate-950 text-blue-600 focus:ring-blue-500">
                Aktif (tampil di halaman publik &amp; pilihan pendaftaran)
            </label>

            @error('status') <p class="{{ $galat }}">{{ $message }}</p> @enderror

            <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-500 text-white font-bold text-sm rounded-xl shadow-sm dark:shadow-none transition">
                Simpan Dinas
            </button>
        </form>
    </div>

    {{-- Tabel Daftar Dinas --}}
    <x-admin.table-card title="Daftar Dinas Terdaftar" title-size="text-base md:text-lg">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-950/50 border-b border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 font-bold text-xs uppercase tracking-wider">
                    <th class="p-4 px-6">No</th>
                    <th class="p-4 px-6">Nama Dinas</th>
                    <th class="p-4 px-6">Alamat</th>
                    <th class="p-4 px-6">Kop Surat</th>
                    <th class="p-4 px-6">Status</th>
                    <th class="p-4 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm font-medium text-slate-700 dark:text-slate-300">
                @forelse($dinasList ?? [] as $index => $dinas)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                        <td class="p-4 px-6 font-bold text-slate-900 dark:text-slate-100">
                            {{ method_exists($dinasList, 'firstItem') ? $dinasList->firstItem() + $index : $index + 1 }}
                        </td>
                        <td class="p-4 px-6">{{ $dinas->nama_dinas }}</td>
                        <td class="p-4 px-6 text-slate-500 dark:text-slate-400 text-xs">{{ $dinas->alamat ?: '-' }}</td>
                        <td class="p-4 px-6 text-xs">
                            @if ($kurang = $dinas->kopKurang())
                                <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300 font-bold">Belum lengkap</span>
                                <span class="block mt-1 text-slate-400">{{ implode(', ', $kurang) }}</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300 font-bold">Lengkap</span>
                            @endif
                        </td>
                        <td class="p-4 px-6">
                            @if($dinas->status)
                                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300 text-xs font-bold">Aktif</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400 text-xs font-bold">Nonaktif</span>
                            @endif
                        </td>
                        <td class="p-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.dinas.edit', $dinas->id) }}"
                                   class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:hover:bg-blue-500/20 dark:text-blue-300 rounded-xl text-xs font-bold transition">Edit</a>
                                <x-admin.delete-button :action="route('admin.dinas.destroy', $dinas->id)" confirm="Yakin ingin menghapus dinas ini? Bidang/pendaftaran yang terhubung mungkin ikut terdampak." />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400 dark:text-slate-500 font-bold">Belum ada dinas terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>

    @if(isset($dinasList) && method_exists($dinasList, 'links'))
        <div>{{ $dinasList->links() }}</div>
    @endif

@endsection