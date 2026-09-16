@extends('admin.layout.app')

@section('page-title', 'Edit Dinas')

@section('content')

    <a href="{{ route('admin.dinas.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-800 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Daftar Dinas
    </a>

    <div class="bg-white rounded-3xl border border-slate-200 p-5 md:p-6 shadow-sm">
        <h3 class="font-extrabold text-slate-900 text-base mb-4">Edit Dinas: {{ $dinas->nama_dinas }}</h3>

        <form action="{{ route('admin.dinas.update', $dinas->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <x-admin.field label="Nama Dinas">
                <input type="text" name="nama_dinas" value="{{ old('nama_dinas', $dinas->nama_dinas) }}" required class="w-full px-4 py-3 rounded-xl border {{ $errors->has('nama_dinas') ? 'border-rose-500' : 'border-slate-200' }} text-sm bg-slate-50">
                @error('nama_dinas') <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p> @enderror
            </x-admin.field>

            <x-admin.field label="Alamat (opsional)">
                <input type="text" name="alamat" value="{{ old('alamat', $dinas->alamat) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50">
            </x-admin.field>

            <x-admin.field label="Deskripsi (opsional)">
                <textarea name="deskripsi" rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm bg-slate-50">{{ old('deskripsi', $dinas->deskripsi) }}</textarea>
            </x-admin.field>

            <label class="flex items-center gap-2.5 text-sm font-semibold text-slate-700">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" name="status" value="1" {{ old('status', $dinas->status) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                Aktif (tampil di halaman publik & pilihan pendaftaran)
            </label>

            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-sm transition">Simpan Perubahan</button>
        </form>
    </div>

@endsection