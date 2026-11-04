{{-- resources/views/partials/tailwind-tema.blade.php --}}
{{--
    SATU baris untuk semua halaman. Di <head> setiap layout/halaman, GANTI baris:
        <script src="https://cdn.tailwindcss.com"></script>
    menjadi:
        @include('partials.tailwind-tema')
    Isinya: (1) pasang tema tersimpan sebelum render, (2) Tailwind CDN,
    (3) config darkMode. Urutannya sudah benar di sini.
--}}
<script>
    // 1) Pasang tema tersimpan SEBELUM halaman dirender (tanpa berkedip)
    (function () {
        try {
            var t = localStorage.getItem('theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        } catch (e) {}
    })();

    function toggleTheme() {
        var dark = document.documentElement.classList.toggle('dark');
        try { localStorage.setItem('theme', dark ? 'dark' : 'light'); } catch (e) {}
        window.dispatchEvent(new CustomEvent('themechange', { detail: { dark: dark } }));
    }

    // Sinkronkan antar tab
    window.addEventListener('storage', function (e) {
        if (e.key !== 'theme') return;
        var dark = e.newValue === 'dark';
        document.documentElement.classList.toggle('dark', dark);
        window.dispatchEvent(new CustomEvent('themechange', { detail: { dark: dark } }));
    });
</script>
<style>html.dark { color-scheme: dark; }</style>

{{-- 2) Tailwind CDN + 3) config dark mode (harus tepat setelah CDN) --}}
<script src="https://cdn.tailwindcss.com"></script>
@include('partials.tailwind-config')