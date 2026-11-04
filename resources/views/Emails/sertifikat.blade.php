<x-mail::message>
# Selamat atas Selesainya Magang Anda!

Yth. **{{ $nama }}**,

Terima kasih atas dedikasi dan kontribusi Anda selama menjalani magang di **{{ $p->dinas_tujuan }}** pada periode **{{ $periode }}**. Sebagai bentuk apresiasi, sertifikat magang Anda telah resmi diterbitkan dan kami lampirkan pada email ini dalam format PDF. Sertifikat juga dapat diunduh kapan saja melalui halaman status pendaftaran Anda.

<x-mail::button :url="$url">
Buka Halaman Status Pendaftaran
</x-mail::button>

Semoga pengalaman yang Anda peroleh bermanfaat bagi studi dan karier Anda ke depan. Kami berharap dapat bekerja sama kembali di kesempatan berikutnya.

Hormat kami,<br>
{{ config('app.name') }}
</x-mail::message>