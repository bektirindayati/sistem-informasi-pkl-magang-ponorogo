{{--
    Pembungkus standar halaman user.
      width : lebar konten. Default max-w-6xl (= hero dashboard lama). Navbar
              terlihat selebar max-w-7xl; kalau ingin tepi konten sejajar
              navbar, ganti default di bawah menjadi max-w-7xl (cukup 1 tempat).
      gap   : jarak antar blok. Default space-y-6, dashboard memakai space-y-8.
    Jarak dari navbar diurus <main class="pt-24 sm:pt-28"> di layout,
    jadi JANGAN menambah padding-top lagi di halaman.
--}}

@props(['width' => 'max-w-6xl', 'gap' => 'space-y-6'])

<div class="bg-slate-50 dark:bg-slate-950 pb-16 transition-colors duration-300">
    <div class="px-4 sm:px-6">
        <div class="{{ $width }} mx-auto {{ $gap }}">
            {{ $slot }}
        </div>
    </div>
</div>