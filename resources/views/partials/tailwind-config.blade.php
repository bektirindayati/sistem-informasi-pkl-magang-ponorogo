{{-- resources/views/partials/tailwind-config.blade.php --}}
{{-- Pakai SETELAH <script src="https://cdn.tailwindcss.com"></script> --}}
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    // Shade gelap slate dibuat navy supaya hero, kartu, navbar, dan footer menyatu
                    slate: {
                        700: '#2a3d63',   // border kuat / hover
                        800: '#1a3260',   // border, chip, hover kartu
                        900: '#0f2142',   // kartu & panel
                        950: '#0a1730',   // latar halaman
                    },
                },
            },
        },
    }
</script>
<style>
    html.dark { color-scheme: dark; } /* scrollbar, input date, dsb. ikut gelap */

    /* ===== Teks mode gelap: naikkan semua abu menjadi putih / putih kebiruan ===== */
    html.dark body                 { color: #f8fafc; }
    html.dark .dark\:text-slate-200 { color: #ffffff; }
    html.dark .dark\:text-slate-300 { color: #f1f5f9; }
    html.dark .dark\:text-slate-400 { color: #e2e8f0; }
    html.dark .dark\:text-slate-500 { color: #cbd5e1; }
    html.dark .dark\:text-slate-600 { color: #cbd5e1; }

    /* Placeholder tetap terbaca tapi lebih redup dari isi */
    html.dark input::placeholder,
    html.dark textarea::placeholder { color: #94a3b8 !important; opacity: 1; }

    /* Input, select, textarea: teks selalu putih */
    html.dark input,
    html.dark select,
    html.dark textarea { color: #f8fafc; }

    /* Daftar pilihan dropdown bawaan browser (sebelumnya gelap di atas gelap) */
    html.dark select option,
    html.dark select optgroup { background-color: #0f2142; color: #f8fafc; }
</style>