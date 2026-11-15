@extends('admin.layout.app')

@section('page-title', 'Edit Dinas')

@section('content')

    @if(session('success'))
        <x-alert type="success"> {{ session('success') }}</x-alert>
    @endif

    <a href="{{ route('admin.dinas.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-semibold transition
              text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="m15 19-7-7 7-7"/>
        </svg>
        Kembali ke Daftar Dinas
    </a>

    <section class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm dark:shadow-none overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800">
            <h3 class="text-base font-extrabold text-slate-900 dark:text-white">Edit Dinas: {{ $dinas->nama_dinas }}</h3>
            <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400">
                Perbarui informasi dinas, kop surat, dan pejabat penandatangan surat balasan.
            </p>
        </div>

        <form action="{{ route('admin.dinas.update', $dinas->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            @include('admin.dinas._form', ['dinas' => $dinas])
        </form>
    </section>

@endsection