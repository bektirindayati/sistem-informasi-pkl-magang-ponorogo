{{-- resources/views/components/form/select.blade.php --}}
{{-- Dropdown biasa. Tambah atribut `cari` untuk mode pencarian (tampil 10 pilihan, saring saat mengetik). --}}

@props([
    'label',
    'name',
    'required' => false,
    'hint' => null,
    'cari' => false,
    'satuan' => 'data',
    'cariPlaceholder' => 'Ketik untuk mencari...',
])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);

    $batas = $hasError
        ? 'border-rose-500 bg-rose-50/50 dark:bg-rose-500/10 dark:border-rose-500'
        : 'border-slate-300 bg-white dark:border-slate-600 dark:bg-slate-900';
@endphp

<div @if ($cari) data-kombo data-satuan="{{ $satuan }}" @endif>
    {{-- Label --}}
    <label
        for="{{ $id }}"
        class="block text-sm font-semibold
               text-slate-700 dark:text-slate-200
               mb-1.5"
    >
        {{ $label }}

        @if ($required)
            <span class="text-rose-500">*</span>
        @endif
    </label>

    {{-- Kolom ketik + daftar (aktif lewat JavaScript; select asli tetap menyimpan nilainya) --}}
    @if ($cari)
        <div class="relative hidden" data-kombo-ui>
            {{-- atribut form menunjuk id yang tidak ada: kolom ini tidak ikut terkirim atau memicu auto-save --}}
            <input
                type="text"
                id="{{ $id }}Cari"
                form="tanpa-form"
                role="combobox"
                aria-expanded="false"
                aria-controls="{{ $id }}Daftar"
                aria-autocomplete="list"
                autocomplete="off"
                spellcheck="false"
                placeholder="{{ $cariPlaceholder }}"
                data-kombo-input
                class="w-full pl-4 pr-10 py-3 rounded-xl border text-sm font-medium
                       outline-none transition-colors duration-200 {{ $batas }}
                       text-slate-800 dark:text-slate-100
                       placeholder:text-slate-400 dark:placeholder:text-slate-500
                       focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500
                       dark:focus:ring-cyan-500/20 dark:focus:border-cyan-500
                       shadow-sm dark:shadow-none"
            >

            <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="6 9 12 15 18 9"/>
            </svg>

            <ul id="{{ $id }}Daftar" role="listbox" data-kombo-list
                class="hidden absolute z-30 mt-1 w-full max-h-[min(28rem,60vh)] overflow-auto rounded-xl
                       border border-slate-200 dark:border-slate-600
                       bg-white dark:bg-slate-900 py-1 shadow-lg"></ul>
        </div>
    @endif

    {{-- Select --}}
    <select
        name="{{ $name }}"
        id="{{ $id }}"
        @if ($required) required @endif
        {{ $attributes->except(['id', 'class'])->merge([
            'class' =>
                'w-full px-4 py-3 rounded-xl border text-sm font-medium ' .
                'outline-none transition-colors duration-200 ' .

                ($hasError
                    ? 'border-rose-500 bg-rose-50/50 dark:bg-rose-500/10 dark:border-rose-500'
                    : 'border-slate-300 bg-white dark:border-slate-600 dark:bg-slate-900') .

                ' text-slate-800 dark:text-slate-100' .
                ' focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500' .
                ' dark:focus:ring-cyan-500/20 dark:focus:border-cyan-500' .

                ' shadow-sm dark:shadow-none',
        ]) }}
    >
        {{ $slot }}
    </select>

    {{-- Pesan validasi --}}
    @error($name)
        <p class="text-xs text-rose-600 dark:text-rose-400 mt-1.5 font-medium">
            {{ $message }}
        </p>
    @enderror

    {{-- Keterangan tambahan --}}
    @if ($hint)
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
            {{ $hint }}
        </p>
    @endif
</div>

@once
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const MAKS = 10;   // jumlah pilihan yang tampil sekaligus

            // Menebalkan bagian teks yang cocok dengan kata pertama yang diketik.
            function sorot(teks, kata) {
                const frag = document.createDocumentFragment();
                const i = kata ? teks.toLowerCase().indexOf(kata) : -1;

                if (i < 0) {
                    frag.append(teks);
                    return frag;
                }

                const tebal = document.createElement('strong');
                tebal.className = 'font-bold text-blue-700 dark:text-cyan-300';
                tebal.textContent = teks.slice(i, i + kata.length);
                frag.append(teks.slice(0, i), tebal, teks.slice(i + kata.length));

                return frag;
            }

            function aktifkan(wrap) {
                const select = wrap.querySelector('select');
                const ui     = wrap.querySelector('[data-kombo-ui]');
                const input  = ui.querySelector('[data-kombo-input]');
                const daftar = ui.querySelector('[data-kombo-list]');
                const satuan = wrap.dataset.satuan || 'data';

                let tampil = [];   // opsi yang sedang ditampilkan
                let items  = [];   // elemen <li> pasangannya
                let aktif  = -1;   // indeks yang sedang disorot

                const terbuka = () => !daftar.classList.contains('hidden');
                const sinkron = () => { input.value = select.value ? select.selectedOptions[0].text.trim() : ''; };

                const tutup = () => {
                    daftar.classList.add('hidden');
                    input.setAttribute('aria-expanded', 'false');
                    input.removeAttribute('aria-activedescendant');
                    aktif = -1;
                };

                const tandai = n => {
                    aktif = n;
                    items.forEach((li, i) => {
                        li.classList.toggle('bg-slate-100', i === n);
                        li.classList.toggle('dark:bg-slate-800', i === n);
                    });

                    if (items[n]) {
                        input.setAttribute('aria-activedescendant', items[n].id);
                        items[n].scrollIntoView({ block: 'nearest' });
                    }
                };

                const pilih = opsi => {
                    const berubah = select.value !== opsi.value;

                    select.value = opsi.value;
                    sinkron();
                    tutup();

                    // Memilih ulang pilihan yang sama tidak boleh mengosongkan bidang.
                    if (berubah) select.dispatchEvent(new Event('change', { bubbles: true }));
                };

                const info = teks => {
                    const li = document.createElement('li');
                    li.setAttribute('role', 'presentation');
                    li.className = 'px-4 py-2 text-xs text-slate-500 dark:text-slate-400 border-t border-slate-100 dark:border-slate-700';
                    li.textContent = teks;
                    return li;
                };

                function buka(kata = '') {
                    const token = kata.trim().toLowerCase().split(/\s+/).filter(Boolean);
                    const semua = [...select.options].filter(o => o.value !== '');
                    const cocok = semua.filter(o => token.every(t => o.text.toLowerCase().includes(t)));

                    tampil = cocok.slice(0, MAKS);
                    daftar.replaceChildren();

                    items = tampil.map((o, n) => {
                        const li = document.createElement('li');
                        li.id = `${daftar.id}-${n}`;
                        li.setAttribute('role', 'option');
                        li.setAttribute('aria-selected', String(o.value === select.value));
                        li.className = 'cursor-pointer px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 aria-selected:font-bold';
                        li.append(sorot(o.text.trim(), token[0] ?? ''));

                        // mousedown (bukan click) + preventDefault: kolom ketik tidak kehilangan fokus.
                        li.addEventListener('mousedown', e => { e.preventDefault(); pilih(o); });
                        li.addEventListener('mousemove', () => tandai(n));

                        daftar.append(li);
                        return li;
                    });

                    const nama = satuan.charAt(0).toUpperCase() + satuan.slice(1);

                    if (!tampil.length) {
                        daftar.append(info(`${nama} tidak ditemukan.`));
                    } else if (cocok.length > tampil.length) {
                        daftar.append(info(`Menampilkan ${tampil.length} dari ${cocok.length} ${satuan}. Ketik untuk mempersempit.`));
                    }

                    // Tanpa ketikan: sorot pilihan yang sedang terpilih bila ada di antara yang tampil.
                    const awal = token.length ? -1 : tampil.findIndex(o => o.value === select.value);

                    daftar.classList.remove('hidden');
                    input.setAttribute('aria-expanded', 'true');
                    tandai(tampil.length ? Math.max(0, awal) : -1);
                }

                input.addEventListener('focus', () => { input.select(); buka(); });
                input.addEventListener('click', () => { if (!terbuka()) buka(); });
                input.addEventListener('input', () => buka(input.value));

                input.addEventListener('keydown', e => {
                    if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                        e.preventDefault();

                        if (!terbuka()) return buka();
                        if (!tampil.length) return;

                        tandai((aktif + (e.key === 'ArrowDown' ? 1 : -1) + tampil.length) % tampil.length);
                    } else if (e.key === 'Enter' && terbuka() && tampil[aktif]) {
                        e.preventDefault();
                        pilih(tampil[aktif]);
                    } else if (e.key === 'Escape') {
                        tutup();
                        sinkron();
                    }
                });

                // Teks yang diketik tetapi tidak dipilih dikembalikan ke pilihan terakhir.
                input.addEventListener('blur', () => {
                    tutup();
                    sinkron();
                    select.dispatchEvent(new Event('blur'));   // memicu validasi & auto-save yang terpasang di select
                });

                select.classList.add('hidden');
                ui.classList.remove('hidden');
                wrap.querySelector('label')?.setAttribute('for', input.id);
                select.addEventListener('change', sinkron);   // nilai diubah kode lain (mis. pemulihan data)
                wrap.sinkron = sinkron;
                sinkron();
            }

            // Dipanggil setelah nilai select diisi ulang lewat kode (mis. pemulihan backup).
            window.sinkronKombo = () => document.querySelectorAll('[data-kombo]').forEach(w => w.sinkron?.());

            document.querySelectorAll('[data-kombo]').forEach(aktifkan);
        });
    </script>
@endonce