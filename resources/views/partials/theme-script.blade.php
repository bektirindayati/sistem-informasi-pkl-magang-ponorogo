{{-- resources/views/partials/theme-script.blade.php --}}
{{-- Di-include di <head> SEMUA layout, SEBELUM script Tailwind --}}
<script>
    // Pasang tema sebelum halaman dirender, supaya tidak berkedip terang dulu
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

    // Sinkronkan antar tab: ganti tema di satu tab, tab lain (user/admin) ikut berubah
    window.addEventListener('storage', function (e) {
        if (e.key !== 'theme') return;
        var dark = e.newValue === 'dark';
        document.documentElement.classList.toggle('dark', dark);
        window.dispatchEvent(new CustomEvent('themechange', { detail: { dark: dark } }));
    });
</script>
<style>html.dark { color-scheme: dark; }</style>