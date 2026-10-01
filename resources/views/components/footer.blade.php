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

    {{-- Website Pemkab Ponorogo --}}
    <a href="https://ponorogo.go.id"
       target="_blank"
       rel="noopener noreferrer"
       class="w-8 h-8 bg-slate-800 hover:bg-blue-600 text-white rounded-lg flex items-center justify-center transition shadow-sm"
       title="Website Pemkab Ponorogo">

        <span class="text-sm">🌐</span>
    </a>

    {{-- WhatsApp --}}
    <a href="https://wa.me/628XXXXXXXXXX"
       target="_blank"
       rel="noopener noreferrer"
       class="w-8 h-8 bg-slate-800 hover:bg-green-600 text-white rounded-lg flex items-center justify-center transition shadow-sm"
       title="WhatsApp">

        <svg xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 24 24"
             class="w-4 h-4 fill-current"
             aria-hidden="true">
            <path d="M20.52 3.48A11.86 11.86 0 0 0 12.04 0C5.5 0 .18 5.32.18 11.86c0 2.09.55 4.13 1.59 5.93L.1 24l6.35-1.67a11.82 11.82 0 0 0 5.59 1.41h.01c6.54 0 11.86-5.32 11.86-11.86 0-3.17-1.23-6.15-3.39-8.4ZM12.05 21.74h-.01a9.86 9.86 0 0 1-5.03-1.38l-.36-.21-3.77.99 1.01-3.67-.23-.38a9.88 9.88 0 1 1 8.39 4.65Zm5.42-7.4c-.3-.15-1.78-.88-2.05-.98-.28-.1-.47-.15-.67.15-.2.3-.77.98-.94 1.18-.17.2-.35.22-.65.07-.3-.15-1.27-.47-2.42-1.5-.9-.8-1.5-1.78-1.68-2.08-.17-.3-.02-.46.13-.61.14-.14.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.03 1.01-1.03 2.47s1.06 2.86 1.21 3.05c.15.2 2.08 3.18 5.04 4.46.7.3 1.25.48 1.68.62.71.23 1.35.2 1.86.12.57-.08 1.78-.73 2.03-1.43.25-.7.25-1.3.17-1.43-.07-.12-.27-.2-.57-.35Z"/>
        </svg>
    </a>

    {{-- Instagram --}}
    <a href="https://instagram.com/USERNAME"
       target="_blank"
       rel="noopener noreferrer"
       class="w-8 h-8 bg-slate-800 hover:bg-pink-600 text-white rounded-lg flex items-center justify-center transition shadow-sm"
       title="Instagram">

        <svg xmlns="http://www.w3.org/2000/svg"
             viewBox="0 0 24 24"
             class="w-4 h-4 fill-current"
             aria-hidden="true">
            <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2Zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5a4.25 4.25 0 0 0 4.25 4.25h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5a4.25 4.25 0 0 0-4.25-4.25h-8.5Zm8.88 2.38a1.13 1.13 0 1 1 0 2.25 1.13 1.13 0 0 1 0-2.25ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"/>
        </svg>
    </a>

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
