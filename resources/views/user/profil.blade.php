@extends('user.layout.app')

@section('title', 'Profil Saya')

@section('content')
<x-page>

    <x-page-header
        eyebrow="Akun"
        title="Profil Saya"
        :back="route('user.dashboard')"
    >
        Data ini otomatis terisi di formulir pendaftaran magang berikutnya.
    </x-page-header>

    @if(session('success'))
        <x-alert type="success"> {{ session('success') }}</x-alert>
    @endif
    @if(session('error'))
        <x-alert type="error">⚠️ {{ session('error') }}</x-alert>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- IDENTITAS AKUN (dari Google, tidak bisa diedit di sini) --}}
        <x-card class="lg:col-span-1">
            <div class="flex items-center gap-4">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                         class="w-14 h-14 rounded-full object-cover border border-slate-200 shrink-0">
                @else
                    <div class="w-14 h-14 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-lg shrink-0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div class="min-w-0">
                    <h2 class="text-base font-semibold text-slate-900 truncate">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500 truncate">{{ $user->email }}</p>
                </div>
            </div>

            <p class="mt-4 pt-4 border-t border-slate-100 text-xs text-slate-400 leading-relaxed">
                Nama & email mengikuti akun Google yang kamu pakai login, jadi tidak bisa diubah di sini.
            </p>
        </x-card>

        {{-- DATA UNTUK AUTO-FILL PENDAFTARAN --}}
        <x-card class="lg:col-span-2">
            <h2 class="text-base font-semibold text-slate-900">Data Akademik</h2>
            <p class="text-sm text-slate-500 mt-1">
                Diisi otomatis setiap kali kamu membuka formulir pendaftaran baru, tidak perlu ketik ulang.
            </p>

            <form action="{{ route('user.profil.update') }}" method="POST" class="mt-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.input name="nim_nisn" label="NIM / NISN" placeholder="Contoh: 22050974001" :value="$user->nim_nisn" />
                    <x-form.input name="jurusan" label="Jurusan / Program Studi" placeholder="S1 Teknologi Pendidikan" :value="$user->jurusan" />

                    <div class="md:col-span-2">
                        <x-form.input name="instansi" label="Asal Instansi / Universitas" placeholder="Universitas Negeri Surabaya" :value="$user->instansi" />
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t border-slate-100">
                    <x-button type="submit">Simpan Perubahan</x-button>
                </div>
            </form>
        </x-card>
    </div>

</x-page>
@endsection