@props(['pendaftaran'])

@if ($pendaftaran->tipe === 'kelompok')
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-semibold
                 bg-indigo-50 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300">
        Kelompok · {{ ($pendaftaran->anggotas_count ?? $pendaftaran->anggotas->count()) + 1 }} orang
    </span>
@endif