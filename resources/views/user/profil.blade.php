@extends('user.layout.app')

@section('title', 'Profil Saya')

@section('content')

<div class="min-h-screen bg-slate-50 pb-14 px-4 sm:px-6">
    <div class="max-w-2xl mx-auto">

        <div class="mb-5">
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">Profil Saya</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Data ini otomatis terisi di formulir pendaftaran magang berikutnya.</p>
        </div>

        @if(session('success'))
    <x-alert type="success"> {{ session('success') }}</x-alert>
@endif

@if(session('error'))
    <x-alert type="error">⚠️ {{ session('error') }}</x-alert>
@endif

        {{-- IDENTITAS AKUN (dari Google, tidak bisa diedit di sini) --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm mt-4 flex items-center gap-4">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-16 h-16 rounded-full object-cover border border-slate-200">
            @else
                <div class="w-16 h-16 rounded-full bg-blue-600 text-white font-extrabold flex items-center justify-center text-xl">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div class="min-w-0">
                <h2 class="font-bold text-slate-900">{{ $user->name }}</h2>
                <p class="text-xs text-slate-500 truncate">{{ $user->email }}</p>
                <p class="text-[11px] text-slate-400 mt-1">Nama & email mengikuti akun Google yang kamu pakai login — tidak bisa diubah di sini.</p>
            </div>
        </div>

        {{-- DATA UNTUK AUTO-FILL PENDAFTARAN --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm mt-4">
            <h3 class="font-bold text-slate-900 text-sm mb-1">Data Akademik</h3>
            <p class="text-xs text-slate-500 mb-4">Diisi otomatis setiap kali kamu membuka formulir pendaftaran baru — tidak perlu ketik ulang.</p>

            <form action="{{ route('user.profil.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <x-form.input name="nim_nisn" label="NIM / NISN" placeholder="Contoh: 22050974001" :value="$user->nim_nisn" />
                <x-form.input name="instansi" label="Asal Instansi / Universitas" placeholder="Universitas Negeri Surabaya" :value="$user->instansi" />
                <x-form.input name="jurusan" label="Jurusan / Program Studi" placeholder="S1 Teknologi Pendidikan" :value="$user->jurusan" />

                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/20 transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('user.dashboard') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition">&larr; Kembali ke Dashboard</a>
        </div>
    </div>
</div>
@endsection