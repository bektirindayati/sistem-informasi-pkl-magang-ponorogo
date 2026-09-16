@extends('admin.layout.app')

@section('page-title', 'Dashboard Admin')

@section('content')



    {{-- Welcome Banner — disamakan ke skema cyan (dulu gradasi biru→indigo, beda sendiri dari sidebar) --}}
    <div class="bg-cyan-600 rounded-3xl p-6 md:p-8 text-white shadow-lg relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="relative z-10 max-w-xl">
            <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-[10px] md:text-xs font-bold uppercase tracking-wider mb-3 inline-block">
                Panel Kontrol Utama
            </span>
            <h2 class="text-xl md:text-3xl font-extrabold tracking-tight mb-2">Selamat Datang, Administrator!</h2>
            <p class="text-cyan-100 text-xs md:text-sm font-medium leading-relaxed">
                Kelola data pengajuan magang dan PKL mahasiswa/siswa dengan cepat dan transparan melalui sistem terintegrasi.
            </p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        <x-admin.stat-card icon="📄" color="cyan" label="Total Mahasiswa" :value="$totalMagang ?? 0" />
        <x-admin.stat-card icon="⏳" color="amber" label="Total Siswa PKL" :value="$totalPkl ?? 0" />
        <x-admin.stat-card icon="✅" color="emerald" label="Menunggu Review" :value="$totalPending ?? 0" class="sm:col-span-2 lg:col-span-1" />
    </div>

    {{-- Tabel Pengajuan Terbaru --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 md:p-6 border-b border-slate-100 flex items-center justify-between gap-4">
            <div>
                <h3 class="font-extrabold text-slate-900 text-base md:text-lg">Daftar Pengajuan Masuk</h3>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">Daftar mahasiswa/siswa yang baru saja mengirimkan berkas.</p>
            </div>
            <a href="{{ route('admin.pendaftar.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition shrink-0">Lihat Semua →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200 text-slate-500 font-bold text-xs uppercase tracking-wider">
                        <th class="p-4 px-6">Nama Pendaftar</th>
                        <th class="p-4 px-6">Asal Instansi / Sekolah</th>
                        <th class="p-4 px-6">Kategori</th>
                        <th class="p-4 px-6">Bidang Tujuan</th>
                        <th class="p-4 px-6">Status</th>
                        <th class="p-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
          <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
    @forelse($pendaftarTerbaru ?? [] as $item)
        <tr class="hover:bg-slate-50/60 transition duration-150">
            <td class="p-4 px-6 font-bold text-slate-900">{{ $item->nama_lengkap ?? $item->nama ?? '-' }}</td>
            <td class="p-4 px-6 text-slate-600 font-medium">{{ $item->asal_instansi ?? $item->instansi ?? $item->asal_sekolah ?? '-' }}</td>
            <td class="p-4 px-6">
                <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                    {{ $item->kategori ?? $item->jenis ?? 'Mahasiswa/Siswa' }}
                </span>
            </td>
            <td class="p-4 px-6 max-w-xs">
                @php $bidangTujuan = $item->bidang?->nama_bidang ?? $item->divisi ?? '-'; @endphp
                <span class="inline-block px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold truncate max-w-full" title="{{ $bidangTujuan }}">
                    {{ $bidangTujuan }}
                </span>
            </td>
            <td class="p-4 px-6">
                @php $status = strtolower($item->status ?? 'pending'); @endphp
                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wide
                    {{ $status == 'diterima' ? 'bg-emerald-100 text-emerald-800' : '' }}
                    {{ $status == 'ditolak' ? 'bg-rose-100 text-rose-800' : '' }}
                    {{ $status == 'pending' ? 'bg-amber-100 text-amber-800' : '' }}">
                    {{ ucfirst($status) }}
                </span>
            </td>
            <td class="p-4 px-6 text-center">
                <div class="flex items-center justify-center gap-2">
                    <a href="{{ route('admin.pendaftar.show', $item->id) }}" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">Detail</a>
                    @if($status == 'diterima')
                        <a href="{{ route('admin.pendaftar.download', $item->id) }}" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition">Unduh</a>
                    @else
                        <a href="{{ route('admin.verifikasi.index') }}" class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow-sm transition">Review</a>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="p-10 text-center text-slate-400 font-semibold">Belum ada data pengajuan magang atau PKL yang masuk ke Dinas Kominfo.</td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>
    </div>

@endsection