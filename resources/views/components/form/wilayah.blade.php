{{--
    <x-form.wilayah :provinsi="$user->provinsi" :kabupaten="$user->kabupaten" />
    Provinsi & kabupaten/kota: bisa dipilih dari saran (config/wilayah.php) atau diketik manual.
    Untuk provinsi yang ada di daftar, saran kabupaten mengikuti provinsi tersebut.
    Letakkan di dalam grid 2 kolom.
--}}
@props(['provinsi' => null, 'kabupaten' => null])

@php($wilayah = config('wilayah', []))

<x-form.input
    name="provinsi"
    id="input-provinsi"
    label="Provinsi"
    required
    list="list-provinsi"
    autocomplete="off"
    placeholder="Ketik atau pilih provinsi"
    hint="Pilih dari daftar, atau ketik manual jika provinsimu tidak ada."
    :value="$provinsi"
/>
<datalist id="list-provinsi">
    @foreach (array_keys($wilayah) as $p)
        <option value="{{ $p }}">
    @endforeach
</datalist>

<x-form.input
    name="kabupaten"
    id="input-kabupaten"
    label="Kabupaten / Kota"
    required
    list="list-kabupaten"
    autocomplete="off"
    placeholder="Ketik atau pilih kabupaten / kota"
    :value="$kabupaten"
/>
<datalist id="list-kabupaten"></datalist>

@once
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const wilayah = @json($wilayah);
    const prov = document.getElementById('input-provinsi');
    const kab  = document.getElementById('input-kabupaten');
    const saran = document.getElementById('list-kabupaten');
    if (!prov || !kab || !saran) return;

    // Daftar kabupaten untuk provinsi yang terdaftar (abaikan huruf besar/kecil); null jika bebas.
    const daftarUntuk = nama => {
        const kunci = Object.keys(wilayah).find(k => k.toLowerCase() === nama.trim().toLowerCase());
        return kunci ? wilayah[kunci] : null;
    };

    // Dipakai validasi form pendaftaran.
    window.kabupatenCocok = (provinsi, kabupaten) => {
        const daftar = daftarUntuk(provinsi);
        return !daftar || daftar.some(n => n.toLowerCase() === kabupaten.trim().toLowerCase());
    };

    const isiSaran = () => {
        saran.innerHTML = '';
        (daftarUntuk(prov.value) || []).forEach(n => {
            const opsi = document.createElement('option');
            opsi.value = n;
            saran.appendChild(opsi);
        });
    };

    prov.addEventListener('input', () => {
        isiSaran();

        // Provinsi terdaftar tapi kabupaten tidak cocok -> kosongkan.
        if (kab.value && !window.kabupatenCocok(prov.value, kab.value)) {
            kab.value = '';
            kab.dispatchEvent(new Event('input', { bubbles: true }));
        }
    });

    isiSaran();
});
</script>
@endpush
@endonce