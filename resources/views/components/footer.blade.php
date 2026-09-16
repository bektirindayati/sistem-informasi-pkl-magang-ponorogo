{{-- Komponen: <x-footer /> --}}
<div style="width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw;" class="bg-slate-900 text-slate-300 pt-12 pb-8 px-6 border-t border-slate-800">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
        <div>
            <div class="flex items-center gap-2.5 mb-3">
                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-black text-sm">M</div>
                <span class="text-white font-extrabold text-sm tracking-wide">MagangHub</span>
            </div>
            <p class="text-[11px] sm:text-xs text-slate-400 leading-relaxed">
                Sistem Informasi Pengelolaan Magang & Praktik Kerja Lapangan (PKL) Terpadu untuk berbagai instansi di lingkungan Pemerintah Kabupaten Ponorogo.
            </p>
        </div>
        <div>
            <h4 class="text-white font-bold text-xs sm:text-sm mb-3 tracking-wider uppercase">Navigasi Halaman</h4>
            <ul class="space-y-2 text-xs">
                <li><a href="#beranda" class="hover:text-blue-400 transition">Beranda Utama</a></li>
                <li><a href="#dokumentasi" class="hover:text-blue-400 transition">Dokumentasi Kegiatan</a></li>
                <li><a href="{{ route('user.pendaftaran.create') }}" class="hover:text-blue-400 transition">Formulir Pendaftaran</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-bold text-xs sm:text-sm mb-3 tracking-wider uppercase">Pusat Bantuan</h4>
            <p class="text-[11px] sm:text-xs text-slate-400 mb-3 leading-relaxed">
                Jika mengalami kendala terkait pendaftaran magang, silakan hubungi narahubung instansi terkait atau admin portal.
            </p>
            <div class="flex items-center gap-2.5">
                <a href="https://ponorogo.go.id" target="_blank" class="w-8 h-8 bg-slate-800 hover:bg-slate-700 text-white rounded-lg flex items-center justify-center text-xs transition shadow-sm" title="Website Pemkab Ponorogo">🌐</a>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto pt-6 border-t border-slate-800/80 flex flex-col sm:flex-row justify-between items-center gap-3 text-[11px] sm:text-xs text-slate-500">
        <p class="text-center sm:text-left">&copy; {{ date('Y') }} Pemerintah Kabupaten Ponorogo. All rights reserved.</p>
        <div class="flex gap-4">
            <span class="hover:text-slate-400 transition">Versi Sistem 2.0.0</span>
        </div>
    </div>
</div>
